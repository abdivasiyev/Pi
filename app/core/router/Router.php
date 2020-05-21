<?php

namespace app\core\router;

use Pi;
use InvalidArgumentException;
use app\core\base\AbstractRouter;

class Router extends AbstractRouter
{
    /**
     * @var string
     */
    private $patternKey = 'pattern';

    /**
     * @var string
     */
    private $controllerKey = 'controller';

    /**
     * @var string
     */
    private $methodKey = 'method';

    /**
     *
     */
    public function initRoutes()
    {
        $routes = $this->getRoutes();

        if (!is_array($routes))
        {
            throw new InvalidArgumentException(
                sprintf("Invalid route array in %s", Pi::$app->configPath)
            );
        }

        foreach ($routes as $key => $route)
        {
            if (isset($route[$this->patternKey]))
            {
                Pi::$app->router->add($key,
                    $route[$this->patternKey],
                    $route[$this->controllerKey],
                    isset($route[$this->methodKey]) ? $route[$this->methodKey] : 'GET'
                );
            }
            else
            {
                $controller = ucfirst($key) . 'Controller:' . $route[1];
                Pi::$app->router->add($key,
                    $route[0],
                    $controller,
                    isset($route[2]) ? $route[2] : 'GET'
                );
            }
        }
    }

    /**
     * @return string[][]
     */
    private function getRoutes()
    {
        if (isset(Pi::$app->config->routes))
        {
            return Pi::$app->config->routes;
        }

        return ['home' => ['/', 'index']];
    }
}