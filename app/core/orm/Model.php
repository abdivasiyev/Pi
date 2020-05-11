<?php

namespace app\orm;

use PDO;
use PDOException;
use app\orm\Database;
use app\exceptions\UnknownPropertyException;
use app\exceptions\UnknownValidatorException;
use app\helpers\Converter;
use app\helpers\Validator;

class Model extends Database
{

    private $fields = [];

    public $values = [];

    private $isValidate = false;

    private $errors = [];

    public $asArray = false;

    protected static $_instance = null;

    public function __construct($schema, $data = False)
    {
        $this->fields['id'] = ['value' => null, 'type' => PDO::PARAM_INT];
        $this->values['id'] = null;
        
        foreach ($schema as $name => $type) {
            $this->fields[$name] = ['value' => null, 'type' => $type];
            $this->values[$name] = null;
        }

        if ($data) {
            foreach ($data as $field => $value) {
                $this->fields[$field]['value'] = $this->bindValueFromDb($field, $value);
                $this->values[$field] = $this->fields[$field]['value'];
            }
        }
    }

    public function getFields()
    {
        return $this->values;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->fields)) {
            $this->fields[$name]['value'] = $this->bindValueFromDb($name, $value);;
            $this->values[$name] = $this->fields[$name]['value'];
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->fields)) {
            return $this->fields[$name]['value'];
        }

        throw new UnknownPropertyException('Unknown table field.', 500);
    }

    public function validate($rules = [])
    {
        $rules = array_merge($this->rules(), $rules);

        $this->isValidate = true;

        if (!empty($rules)) {
            foreach ($rules as $field => $rule) {
                $v = $this->generateValidator($field, $rule, $this->fields[$field]['value']);

                if (!$v->isOk()) {
                    $this->errors[$field] = $v->getErrors()[$field];
                }
            }
        }

        return empty($this->errors);
    }

    private function bindValueFromDb($field, $value)
    {
        if($this->fields[$field]['type'] === PDO::PARAM_INT) {
            $result = Converter::strToInt($value);
        } else {
            $result = $value;
        }

        return $result;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public static function rules()
    {
        return [];
    }

    public function save()
    {

        if ($this->isValidate !== false && !$this->validate()) {
            return false;
        }

        $tableName = self::getTableName();
        $update = false;

        if ($this->fields['id']['value'] !== null) {
            foreach ($this->fields as $field => $params) {
                if ($field !== 'id' && $params['value'] !== null) {
                    $bindName = self::getBindName($field);
                    $set[] = "{$field}={$bindName}";
                }
            }

            $bindName = self::getBindName('id');
            $set = implode(', ', $set);
            $query = "UPDATE {$tableName} SET {$set} WHERE id={$bindName}";
            $update = true;
        } else {
            foreach ($this->fields as $field => $params) {
                $columns[] = $field;
                $insert[] = self::getBindName($field);
            }
            $columns = implode(', ', $columns);
            $insert = implode(', ', $insert);
            $query = "INSERT INTO {$tableName} ";
            $query .= "({$columns}) VALUES ({$insert})";
        }


        $pdo = self::getPdo();

        $statement = $pdo->prepare($query);

        foreach ($this->fields as $field => $params) {
            $statement->bindValue(self::getBindName($field), $params['value'], $params['type']);
        }

        try {
            $pdo->beginTransaction();
            $result = $statement->execute();
            
            if (!$update) {
                $this->fields['id']['value'] = $this->bindValueFromDb('id', $pdo->lastInsertId());
                $this->values['id'] = $this->fields['id']['value'];
            }
            
            $pdo->commit();

            return $result;
        } catch (PDOException $pe) {
            $pdo->rollBack();
            throw new PDOException($pe->getMessage(), 500);
        }
    }

    public function delete()
    {
        if ($this->fields['id'] !== null) {
            $tableName = self::getTableName();
            $bindName = self::getBindName('id');
            $query = "DELETE FROM {$tableName} WHERE id={$bindName}";

            $pdo = self::getPdo();
            $statement = $pdo->prepare($query);

            $statement->bindValue($bindName, $this->fields['id']['value'], $this->fields['id']['type']);

            try {
                $pdo->beginTransaction();
                $result = $statement->execute();
                
                $pdo->commit();
    
                return $result;
            } catch (PDOException $pe) {
                $pdo->rollBack();
                throw new PDOException($pe->getMessage(), 500);
            }
        }
    }

    public function load($data, $keyName = null, $isKeyName = true)
    {
        if ($isKeyName === true) {
            $keyName = $keyName === null ? self::getModelName() : $keyName;
            $keyName = Converter::camelCaseToUnderscore($keyName);

            if (!isset($data[$keyName])) {
                return false;
            } else {
                $data = $data[$keyName];
            }
        }
        $this->loadFromArray($data);
        return true;
    }

    private function loadFromArray($data)
    {
        foreach ($data as $field => $value) {
            $this->$field = $value;
        }
    }

    protected static function getDb()
    {
        return new Database();
    }
    
    protected static function getPdo()
    {
        $db = self::getDb();

        return $db->pdo;
    }

    protected static function getModelName()
    {
        return get_called_class();
    }

    protected static function getTableName()
    {
        $path = explode('\\', self::getModelName());
        return Converter::dashesToCamelCase(array_pop($path), false);
    }

    protected static function getBindName($field)
    {
        return ":{$field}";
    }

    private function generateValidator($field, $rules, $value)
    {
        $v = new Validator();

        $v = $v->fieldName($field)->fieldValue($value);

        if (!isset($rules['validator']) || !in_array($rules['validator'], $this->validate_functions())) {
            throw new UnknownValidatorException('Unknown validator.');
        }

        if (key_exists('default', $rules)) {
            $v = $v->fieldDefault($rules['default']);
        }

        if ($rules['validator'] === 'custom') {
            $v = $v->fieldCustomPattern($rules['pattern']);
        } else {
            $v = $v->fieldPattern($rules['validator']);
        }

        if (isset($rules['min'])) {
            $v = $v->fieldMin($rules['min']);
        }

        if (isset($rules['max'])) {
            $v = $v->fieldMax($rules['max']);
        }

        if (isset($rules['required']) && $rules['required'] === true) {
            $v = $v->fieldRequired();
        }

        if (isset($rules['equals'])) {
            $v = $v->fieldEquals($rules['equals']);
        }

        if (isset($rules['maxFileSize'])) {
            $v = $v->fieldMaxFileSize($rules['maxFileSize']);
        }

        if (isset($rules['extensions'])) {
            $v = $v->fieldFileExtension($rules['extensions']);
        }

        return $v;
    }

    private function validate_functions()
    {
        return [
            'uri',
            'url',
            'alpha',
            'words',
            'alphanum',
            'int',
            'float',
            'phone',
            'text',
            'file',
            'folder',
            'address',
            'date_ymd',
            'datetime',
            'email'
        ];
    }

    public static function getInstance()
    {
        if (self::$_instance === null) {
            $model = self::getModelName();
            self::$_instance = new $model;
        }

        return self::$_instance;
    }

    public static function get($id)
    {
        return self::getBy('id', $id);
    }

    public static function getBy($fieldName, $value)
    {
        $tableName = self::getTableName();
        $bindName = self::getBindName($fieldName);
        $query = "
            SELECT * FROM {$tableName}
                WHERE {$fieldName}={$bindName}
        ";
        $result = self::getDb()->execute($query, [
            $bindName => $value
        ]);

        if (count($result) === 1) {
            $modelName = self::getModelName();

            return self::getInstance()->asArray ? (new $modelName($result[0]))->values : (new $modelName($result[0]));
        } else{
            $models = [];
            if (self::getInstance()->asArray) {
                foreach ($result as $modelData) {
                    $modelName = self::getModelName();
                    $models[] = (new $modelName($modelData))->values;
                }
            } else {
                foreach ($result as $modelData) {
                    $modelName = self::getModelName();
                    $models[] = new $modelName($modelData);
                }
            }

            return $models;
        }

        return self::getInstance()->asArray ? [] : null;
    }

    public function asArray()
    {
        self::getInstance()->asArray = true;

        return self::getInstance();
    }

    public static function getAll()
    {
        $tableName = self::getTableName();
        $query = "SELECT * FROM {$tableName}";
        $result = self::getDb()->execute($query);
        if ($result) {
            $models = [];
            if (self::getInstance()->asArray) {
                foreach ($result as $modelData) {
                    $modelName = self::getModelName();
                    $models[] = (new $modelName($modelData))->values;
                }
            } else {
                foreach ($result as $modelData) {
                    $modelName = self::getModelName();
                    $models[] = new $modelName($modelData);
                }
            }
            
            return $models;
        }
        return self::getInstance()->asArray ? [] : null;
    }

    public static function getAllBy($fieldName, $value)
    {
        $tableName = self::getTableName();
        $bindName = self::getBindName($fieldName);
        $query = "SELECT * FROM {$tableName} WHERE {$fieldName} = {$bindName}";
        
        $result = self::getDb()->execute($query, [
            $bindName => $value
        ]);
        
        if ($result) {
            $models = array();
            foreach ($result as $modelData) {
                $modelName = self::getModelName();
                $models[] = new $modelName($modelData);
            }
            return $models;
        }
        return null;
    }
}