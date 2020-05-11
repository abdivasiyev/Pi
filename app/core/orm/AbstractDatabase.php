<?php

namespace app\orm;

use app\exceptions\DatabaseConnectionException;
use PDO;

abstract class AbstractDatabase {

    private $_config = null;

    public $pdo = null;

    public function __construct($config)
    {
        $this->_config = $config;

        $this->connect();
    }

    private function connect()
    {
        $host = $this->_config['host'];
        $port = $this->_config['port'];
        $engine = $this->_config['engine'];
        $username = $this->_config['username'];
        $password = $this->_config['password'];
        $dbname = $this->_config['dbname'];
        $charset = $this->_config['charset'];

        $dsn = sprintf("%s:dbname=%s;host=%s;port=%s;charset=%s",
            $engine,
            $dbname,
            $host,
            $port,
            $charset);

        try {
            $this->pdo = new PDO(
                $dsn,
                $username,
                $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (DatabaseConnectionException $e) {
            throw new DatabaseConnectionException($e->getMessage(), 500);
        }
    }

    public function execute($query, $attrs = [])
    {
        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare($query);
            if (!empty($attrs)) {
                foreach ($attrs as $bindName => $value) {
                    $statement->bindParam($bindName, $value);
                }
            }
            $statement->execute();
            $this->pdo->commit();
        } catch (DatabaseConnectionException $e) {
            $this->pdo->rollback();
            throw new DatabaseConnectionException($e->getMessage(), 500);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}