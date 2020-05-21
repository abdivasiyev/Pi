<?php

namespace app\core\router;

class DispatchedRoute
{

    /**
     * @var
     */
    private $controller;
    /**
     * @var array
     */
    private $parameters;

    /**
     * DispatchedRoute constructor.
     * @param $controller
     * @param array $parameters
     */
    public function __construct($controller, $parameters = [])
    {
        $this->controller = $controller;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    
}