<?php

namespace app\exceptions;

use Exception;

class UnknownPropertyException extends Exception {

    public function getName()
    {
        return 'Unknown Property Exception';
    }
}