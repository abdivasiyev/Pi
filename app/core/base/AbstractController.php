<?php

namespace app\core\base;

abstract class AbstractController extends AbstractComponent
{
    /**
     *
     */
    public function beforeExecute() {}

    /**
     *
     */
    public function afterExecute() {}

    /**
     * @param $controller
     * @param $method
     * @param $parameters
     * @return mixed
     */
    abstract function init($controller, $method, $parameters);
}