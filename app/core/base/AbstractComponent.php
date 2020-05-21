<?php

namespace app\core\base;

use InvalidArgumentException;

abstract class AbstractComponent
{
    protected $container = [];

    public function __get($key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }

        throw new InvalidArgumentException(sprintf("%s is not exists in the App container.", $key));
    }

    public function __set($key, $value)
    {
        $this->container[$key] = $value;
    }
    
}