<?php

namespace app\core\base;

abstract class AbstractController extends AbstractComponent
{
    public function beforeExecute() {}
    public function afterExecute() {}
    abstract function init($controller, $method, $parameters);
}