<?php

namespace app\core\base;

abstract class AbstractProvider extends AbstractComponent
{
    /**
     * @var
     */
    public $name;

    /**
     * @return mixed
     */
    abstract function init();
}