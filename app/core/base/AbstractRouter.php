<?php

namespace app\core\base;

use app\core\router\Dispatcher;

class AbstractRouter extends AbstractComponent
{
    /**
     * @var array
     */
    protected $routes = [];
    /**
     * @var
     */
    protected $dispatcher;
    /**
     * @var
     */
    protected $host;

    /**
     * AbstractRouter constructor.
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;
    }

    /**
     * @param $key
     * @param $pattern
     * @param $controller
     * @param string $method
     */
    public function add($key, $pattern, $controller, $method = 'GET')
    {
        $this->routes[$key] = [
            'pattern'       => $pattern,
            'controller'    => $controller,
            'method'        => $method
        ];
    }

    /**
     * @param $method
     * @param $uri
     * @return \app\core\router\DispatchedRoute|void
     */
    public function dispatch($method, $uri)
    {
        return $this->getDispatcher()->dispatch($method, $uri);
    }

    /**
     * @return Dispatcher
     */
    public function getDispatcher()
    {
        if ($this->dispatcher === null) {
            $this->dispatcher = new Dispatcher();

            foreach ($this->routes as $route) {
                $this->dispatcher->register(
                    $route['method'],
                    $route['pattern'],
                    $route['controller']
                );
            }
        }

        return $this->dispatcher;
    }
}