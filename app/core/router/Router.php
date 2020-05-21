<?php

namespace app\core\router;

use App;
use InvalidArgumentException;
use app\core\base\AbstractRouter;

class Router extends AbstractRouter
{
    private $routePath;

    private $patternKey = 'pattern';

    private $controllerKey = 'controller';

    private $methodKey = 'method';

    public function __construct($host)
    {
        parent::__construct($host);

        // $this->routePath = App::$app->config->routePath;
        $this->routePath = APP_DIR . 'config/routes.php';
    }

    public function initRoutes()
    {
        $routes = require_once $this->routePath;

        if (!is_array($routes))
        {
            throw new InvalidArgumentException(
                sprintf("Invalid route array in %s", $this->routePath)
            );
        }

        foreach ($routes as $key => $route)
        {
            if (isset($route[$this->patternKey]))
            {
                App::$app->router->add($key,
                    $route[$this->patternKey],
                    $route[$this->controllerKey],
                    isset($route[$this->methodKey]) ? $route[$this->methodKey] : 'GET'
                );
            }
            else
            {
                $controller = ucfirst($key) . 'Controller:' . $route[1];
                App::$app->router->add($key,
                    $route[0],
                    $controller,
                    isset($route[2]) ? $route[2] : 'GET'
                );
            }
        }
    }
}