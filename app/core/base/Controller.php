<?php

namespace app\core\base;

use Pi;
use ReflectionMethod;

class Controller extends AbstractController
{

    protected $view;

    protected $layout;

    protected $layoutPath;

    public function __construct()
    {
        $this->view = Pi::$app->view;
        $this->layoutPath = 'layouts/';
        $this->layout = $this->layoutPath . 'main';
    }

    public function init($controller, $method, $parameters)
    {
        $controller = new $controller;
        call_user_func([$controller, 'beforeExecute']);
        call_user_func_array([$controller, $method], $parameters);
        call_user_func([$controller, 'afterExecute']);
    }

    protected function render($view, $vars = [])
    {
        $view = $this->view->render($view, $vars);

        echo $this->view->render($this->layout, ['content' => $view]);
    }
}