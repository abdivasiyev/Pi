<?php

namespace app\core\base;

abstract class AbstractProvider extends AbstractComponent
{
    public $name;

    abstract function init();
}