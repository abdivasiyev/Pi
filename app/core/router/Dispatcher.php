<?php

namespace app\core\router;

class Dispatcher
{
    /**
     * @var string[]
     */
    private $methods = [
        'GET',
        'POST',
        'HEAD',
        'PUT',
        'DELETE',
        'CONNECT',
        'OPTIONS',
        'TRACE',
        'PATCH',
    ];

    /**
     * @var array[]
     */
    private $routes = [
        'GET'       => [],
        'POST'      => [],
        'HEAD'      => [],
        'PUT'       => [],
        'DELETE'    => [],
        'CONNECT'   => [],
        'OPTIONS'   => [],
        'TRACE'     => [],
        'PATCH'     => [],
    ];

    /**
     * @var string[]
     */
    private $patterns = [
        'int'   => '[0-9]+',
        'str'   => '[a-zA-Z\.\-_%]+',
        'any'   => '[a-zA-Z0-9\.\-_%]+',
        'slug'  => '[a-z0-9]+(?:-[a-z0-9]+)*'
    ];

    /**
     * @param $method
     * @param $pattern
     * @param $controller
     */
    public function register($method, $pattern, $controller)
    {
        $convert = $this->convertPattern($pattern);
        $this->routes[strtoupper($method)][$convert] = $controller;
    }

    /**
     * @param $key
     * @param $pattern
     */
    public function addPattern($key, $pattern)
    {
        $this->patterns[$key] = $pattern;
    }

    /**
     * @param $method
     * @param $uri
     * @return DispatchedRoute
     */
    public function dispatch($method, $uri)
    {
        $routes = $this->routes(strtoupper($method));

        if (array_key_exists($uri, $routes))
        {
            return new DispatchedRoute($routes[$uri]);
        }

        return $this->doDispatch($method, $uri);
    }

    /**
     * @param $method
     * @return array
     */
    private function routes($method)
    {
        return isset($this->routes[$method]) ? $this->routes[$method] : [];
    }

    /**
     * @param $method
     * @param $uri
     * @return DispatchedRoute
     */
    private function doDispatch($method, $uri)
    {
        foreach ($this->routes(strtoupper($method)) as $route => $controller) {
            $pattern = '#^' . $route . '$#s';

            if (preg_match($pattern, $uri, $parameters)) {
                return new DispatchedRoute($controller, $this->processParams($parameters));
            }
        }
    }

    /**
     * @param $pattern
     * @return string|string[]|null
     */
    private function convertPattern($pattern)
    {
        if (strpos($pattern, '(') === false) {
            return $pattern;
        }

        return preg_replace_callback(
            '#\((\w+):(\w+)\)#',
            [$this, 'replacePattern'],
            $pattern
        );
    }

    /**
     * @param $matches
     * @return string
     */
    private function replacePattern($matches)
    {
        return '(?<' . $matches[1] . '>' . strtr($matches[2], $this->patterns) . ')';
    }

    /**
     * @param $parameters
     * @return mixed
     */
    private function processParams($parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_int($key)) {
                unset($parameters[$key]);
            }
        }

        return $parameters;
    }
}