<?php

namespace app\exceptions;

use PDOException;

class DatabaseConnectionException extends PDOException 
{
    public function getName()
    {
        return 'DatabaseConnectionException';
    }
}
