<?php

namespace app\core\renderers;

use app\core\base\AbstractController;
use Pi;

class Controller extends AbstractController
{

    /**
     * @var
     */
    protected $view;

    /**
     * @var string
     */
    protected $layoutPath = 'layouts/';

    protected $layout = 'main';

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = Pi::$app->view;
    }

    /**
     * @param $controller
     * @param $method
     * @param $parameters
     * @return mixed|void
     */
    public function init($controller, $method, $parameters)
    {
        $controller = new $controller;
        call_user_func([$controller, 'beforeExecute']);
        call_user_func_array([$controller, $method], $parameters);
        call_user_func([$controller, 'afterExecute']);
    }

    /**
     * @param $view
     * @param array $vars
     */
    protected function render($view = '', $vars = [])
    {
        $this->layout = $this->layoutPath . $this->layout;

        $view = $this->view->render($view, $vars);

        echo $this->view->render($this->layout, ['content' => $view]);
    }
}