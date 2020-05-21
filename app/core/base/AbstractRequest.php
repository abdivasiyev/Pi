<?php

namespace app\core\base;

abstract class AbstractRequest extends AbstractComponent
{
    abstract function get(string $key = null);
    abstract function post(string $key = null);
}