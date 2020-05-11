<?php

namespace app\helpers;

class Validator
{
    const URI_VALIDATOR = 'uri';

    const URL_VALIDATOR = 'url';

    const ALPHA_VALIDATOR = 'alpha';

    const WORDS_VALIDATOR = 'words';

    const ALPHANUM_VALIDATOR = 'alphanum';

    const INTEGER_VALIDATOR = 'int';

    const FLOAT_VALIDATOR = 'float';

    const PHONE_VALIDATOR = 'phone';

    const TEXT_VALIDATOR = 'text';

    const FILE_VALIDATOR = 'file';

    const FOLDER_VALIDATOR = 'folder';

    const ADDRESS_VALIDATOR = 'address';

    const DATE_VALIDATOR = 'date_ymd';

    const DATETIME_VALIDATOR = 'datetime';

    const EMAIL_VALIDATOR = 'email';

    const CUSTOM_VALIDATOR = 'custom';

    const ARRAY_VALIDATOR = 'array';

    private $name;

    private $label;

    private $value = null;

    private $isDefault = false;

    private $file = null;

    private $errors = [];

    private $patterns = [
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'phone'         => '[0-9+\s()-]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'datetime'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2} [0-2][0-9]\:[0-6][0-9]\:[0-6][0-9]',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    ];

    public function fieldName($name)
    {
        $this->name = $name;
        $this->label = Converter::dashesToCamelCase($name);

        return $this;
    }

    public function getFieldName()
    {
        return $this->name;
    }

    public function fieldValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function fieldFile($value)
    {
        $this->file = $value;

        return $this;
    }

    public function fieldPattern($patternName)
    {
        if (!$this->isDefault) {
            if ($patternName === self::ARRAY_VALIDATOR) {
                if (!is_array($this->value)) {
                    $this->errors[$this->name]['errors'][] = "{$this->label} type must be array.";
                }
            }elseif ($patternName === self::TEXT_VALIDATOR) {
                if (!is_string($this->value)) {
                    $this->errors[$this->name]['errors'][] = "{$this->label} type must be string.";
                }
            }elseif ($patternName === self::INTEGER_VALIDATOR) {
                if (!is_int($this->value)) {
                    $this->errors[$this->name]['errors'][] = "{$this->label} type must be integer.";
                }
            }elseif ($patternName === self::FLOAT_VALIDATOR) {
                if (!is_float($this->value)) {
                    $this->errors[$this->name]['errors'][] = "{$this->label} type must be float.";
                }
            } else {
                $regex = '/^(' . $this->patterns[$patternName] . ')$/u';

                if ($this->value !== '' && !preg_match($regex, $this->value)) {
                    $this->errors[$this->name]['errors'][] =  "{$this->label} must be a valid {$patternName}";
                } 
            }
        }

        return $this;
    }

    public function fieldCustomPattern($pattern)
    {
        $pattern = '/^(' . $pattern . ')$/u';

        if ($this->value !== '' && !preg_match($pattern, $this->value) && !$this->isDefault) {
            $this->errors[$this->name]['errors'][] = "{$this->label} is not valid.";
        }

        return $this;
    }

    public function fieldDefault($value)
    {
        if ($this->value === '' || $this->value === null) {
            $this->value = $value;
            $this->isDefault = true;
        }

        return $this;
    }

    public function fieldRequired()
    {
        if ( (isset($this->file) && $this->file['error'] !== UPLOAD_ERR_NO_FILE) ||
                ($this->value === '' || $this->value === null) ) {
            if (!$this->isDefault)
                $this->errors[$this->name]['errors'][] = "{$this->label} must not be empty.";
        }

        return $this;
    }

    public function fieldMin(int $length)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) < $length) {
                $this->errors[$this->name]['errors'][] = "{$this->label} length must be minimum {$length}";
            }
        } elseif($this->value < $length) {
            $this->errors[$this->name]['errors'][] = "{$this->label} must be minimum {$length}";
        }

        return $this;
    }

    public function fieldMax(int $length)
    {
        if (is_string($this->value)) {
            if (strlen($this->value) > $length) {
                $this->errors[$this->name]['errors'][] = "{$this->label} length must be maximum {$length}";
            }
        } elseif($this->value > $length) {
            $this->errors[$this->name]['errors'][] = "{$this->label} must be maximum {$length}";
        }

        return $this;
    }

    public function fieldEquals($value)
    {
        if ($this->value !== $value) {
            $this->errors[$this->name]['errors'][] = "{$this->label} must be {$this->value}";
        }

        return $this;
    }

    public function fieldMaxFileSize($size)
    {
        if (isset($this->file)
            && $this->file['error'] !== UPLOAD_ERR_NO_FILE
            && $this->file['size'] > $size) {
                $this->errors[$this->name]['errors'][] = "{$this->label} max size must be {$size}";
        }

        return $this;
    }

    public function fieldFileExtension(array $extensions)
    {
        if($this->file['error'] != UPLOAD_ERR_NO_FILE){
            $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);
            $extensionUpper = strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, $extensions) || !in_array($extensionUpper, $extensions)) {
                $this->errors[$this->name]['errors'][] = "{$this->label} must be one of these types: " . implode(', ', $extensions);
            }
        }
        return $this;
    }

    public function isOk()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsAsJson()
    {
        return json_decode($this->errors, JSON_UNESCAPED_UNICODE);
    }
}