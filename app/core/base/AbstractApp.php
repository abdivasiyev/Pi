<?php

namespace app\core\base;

use App;
use ErrorException;
use Whoops\Handler\Handler;
use app\core\base\Controller;
use InvalidArgumentException;
use app\core\router\DispatchedRoute;

abstract class AbstractApp extends AbstractComponent
{

    public static $app;

    public $baseUrl = '/';

    private $servicePath;

    private $runtimePath;

    public function __construct()
    {
        $this->servicePath = APP_DIR . 'app/core/service/service.php';
        $this->runtimePath = APP_DIR . 'runtime/';
    }

    public static function getInstance()
    {
        if (static::$app === null) {
            return new App();
        }

        return static::$app;
    }

    public function run($startTime)
    {
        session_start();
        if ($this->hasRuntime()) {
            $this->logErrors();
        }
        try {
            static::$app = App::getInstance();
            
            $services = $this->loadServices();
            $this->initServices($services);

            $this->initRoutes();

            $this->initController();
            // Logging execution time and accesses.
            $this->logExecution($startTime);
            $this->logAccess();
            // varDump(App::$app->router);
        } catch (ErrorException $e) {
            exit($e->getMessage());
        }
    }

    protected function loadServices()
    {
        $services = require_once $this->servicePath;

        if (!is_array($services)) {
            throw new Exception(sprintf("%s is not valid array.", $this->servicePath));
        }

        return $services;
    }

    protected function initServices($services)
    {
        foreach ($services as $service) {
            $provider = new $service();
            $providerName = $provider->name;
            static::$app->$providerName = $provider->init();
        }
    }

    protected function initRoutes()
    {
        App::$app->router->initRoutes();
    }

    protected function initController()
    {
        try {
            $requestMethod = App::$app->request->server('REQUEST_METHOD');
            $requestUri = App::$app->request->server('REQUEST_URI');
            $requestUri = ($position = strpos($requestUri, '?')) ? $requestUri = substr($requestUri, 0, $position) : $requestUri;
            $routerDispatch = App::$app->router->dispatch(
                $requestMethod,
                $requestUri);

            if (is_null($routerDispatch)) {
                $routerDispatch = new DispatchedRoute('ErrorController:page404');
            }

            list($class, $method) = explode(':', $routerDispatch->getController(), 2);

            $controller = '\\app\\controllers\\' . $class;
            $parameters = $routerDispatch->getParameters();

            call_user_func_array([new Controller(), 'init'], [$controller, $method, $parameters]);
        } catch (Exception $e) {
            throw new $e;
        }
    }

    protected function logExecution($startTime)
    {
        $endTime = microtime(true);

        $executionTime = ($endTime - $startTime);

        $data = sprintf("Executed in %f seconds. Started: [%s]. Endend: [%s]\n",
            $executionTime,
            date("Y-m-d h:i:s", $startTime),
            date("Y-m-d h:i:s", $endTime));

        $logPath = $this->runtimePath . 'execution.log';

        file_put_contents($logPath, $data, FILE_APPEND);
    }

    protected function logErrors()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->pushHandler(function($exception, $inspector, $run) {
            $data = sprintf("[%s] [Error]: %s\n",
                date("Y-m-d h:i:s", time()),
                $exception->getMessage()
            );

            $logPath = $this->runtimePath . 'error.log';

            file_put_contents($logPath, $data, FILE_APPEND);
            return Handler::DONE;
        });
        $whoops->register();
    }

    protected function logAccess()
    {
        $data = sprintf("[%s]:[Requested URL]: %s\n",
            date("Y-m-d h:i:s", time()),
            App::$app->request->server('REQUEST_URI'));

        $logPath = $this->runtimePath . 'access.log';

        file_put_contents($logPath, $data, FILE_APPEND);
    }

    protected function hasRuntime()
    {
        if (!file_exists($this->runtimePath))
            return mkdir($this->runtimePath);

        return true;
    }

}