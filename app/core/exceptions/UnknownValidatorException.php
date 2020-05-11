<?php

namespace app\exceptions;

use Exception;

class UnknownValidatorException extends Exception
{
    public function getName()
    {
        return 'Unknown Validator Exception';
    }
}