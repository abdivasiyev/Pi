<?php

namespace app\core\router;

class DispatchedRoute
{
    
    private $controller;
    private $parameters;

    /**
     * DispatchedRoute constructor.
     */
    public function __construct($controller, $parameters = [])
    {
        $this->controller = $controller;
        $this->parameters = $parameters;
    }

    /**
     * @return $controller
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