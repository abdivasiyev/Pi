<?php

namespace app\base;

use App;
use app\base\View;
use app\helpers\Html;
use app\helpers\Converter;

class Controller {
    
    public $id;

    public $method;

    public $layout = 'main';
    
    private $content;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function render($viewName, $params = [])
    {
        $folder = Converter::camelCaseToDashes($this->id);
        
        $view = new View($viewName);
        $this->content = $view->render($folder . '/' . $viewName, $params);

        return $this->content;
    }

    protected function renderJson($params)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        
        $response = json_encode($params, JSON_UNESCAPED_UNICODE);

        return $response;
    }
}