<?php

namespace app;

use App;
use app\base\Route;
use app\base\Method;
use app\helpers\Html;
use app\request\Request;
use app\helpers\Converter;
use app\helpers\ArrayHelper;

defined('APP_DEBUG') or define('APP_DEBUG', false);

class BaseApp {

    public static $app;

    public $controller = 'home';

    public $method = 'index';

    public $request = null;

    public $params = [];

    public $user = null;

    public $routes = ['home/' => 'home/index'];

    public $config = [];

    public $db = null;

    public function __construct($config, $routes)
    {
        if (!APP_DEBUG) {
            register_shutdown_function(function ()
            {
                $error = error_get_last();
                // to make sure that there is any fatal error
                if (isset($error) &&
                    ($error['type'] == E_ERROR
                    || $error['type'] == E_PARSE
                    || $error['type'] == E_COMPILE_ERROR
                    || $error['type'] == E_CORE_ERROR))
                    {
                        App::redirect('/home/500');
                    }
            });
        }
        
        static::$app = $this;
        $this->debug();
        $this->config = $config;
        $this->request = new Request();

        $this->routes = $routes;
        $url = $this->parseUrl();

        if (isset($url[0])) {
            $this->setController($url[0]);
            $url = ArrayHelper::unset($url, 0);
        } else {
            $this->setController($this->controller);
        }

        if (isset($url[0])) {
            $this->setMethod($url[0]);
            $url = ArrayHelper::unset($url, 0);
        } else {
            $this->setMethod($this->method);
        }

        print_r(call_user_func_array([
            $this->controller,
            $this->controller->method->id
        ],
        $this->params));
    }

    private function setController($c)
    {
        $c = Converter::dashesToCamelCase($c);
        if (file_exists("../app/controllers/{$c}Controller.php")) {
            $this->controller = $c;
            require_once '../app/controllers/' . $this->controller . 'Controller.php';
            $this->controller = "\\app\\controllers\\" . $this->controller . 'Controller';
            $this->controller = new $this->controller($c);
        } else {
            App::redirect('/home/404');
        }
    }

    private function setMethod($m)
    {
        $m = Converter::dashesToCamelCase($m);
        if (method_exists($this->controller, 'method' . $m)) {
            $this->method = $m;
            $this->controller->method = new Method('method' . $m);
        } else {
            App::redirect('/home/404');
        }
    }

    private function parseUrl()
    {
        if (isset(App::$app->request->server()['requestUri']))
        {
            $url = App::$app->request->server()['requestUri'];
            $url = rtrim(ltrim(rtrim($url), '/'), '/');
            $url = filter_var($url, FILTER_SANITIZE_STRING);
            $url = explode('?',$url);

            if (count($url) >= 1) $url = $url[0];

            $url = preg_replace('/(\/+)/','/',$url) . '/';

            foreach ($this->routes as $route => $u) {
                $r = new Route($route, $url);
                if ($r->isOk()) {
                    $url = explode('/',$u);
                    $this->params = $r->getParams();
                    break;
                }
            }
            return $url;
        } else {
            App::redirect('/home/404');
        }
    }

    private function debug()
    {
        if (APP_DEBUG)
        {
            $run     = new \Whoops\Run;
            $handler = new \Whoops\Handler\PrettyPageHandler;

            $run->pushHandler(new \Whoops\Handler\JsonResponseHandler);

            $run->register();
        } else {
            ini_set('display_errors', "off");
            ini_set('display_startup_errors', 0);
            ini_set("error_log", __DIR__ . '/../../runtime/requests.log');
            error_reporting(E_USER_ERROR);
        }
    }

    public static function className()
    {
        return get_called_class();
    }

    public static function redirect($url, $params = []) {
        $url = Html::encode($url);
        $p = [];
        foreach($params as $k => $v) {
            $p[] = Html::encode($k) . '=' . Html::encode($v);
        }

        if (!empty($p))
            header('Location: ' . $url . '?' . implode('&', $p));
        else
            header('Location: ' . $url);
        exit();
    }

}