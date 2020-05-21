<?php

namespace app\core\base;

use InvalidArgumentException;

abstract class AbstractComponent
{
    /**
     * @var array
     */
    protected $container = [];

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        }

        throw new InvalidArgumentException(sprintf("%s is not exists in the container.", $key));
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->container[$key] = $value;
    }
    
}