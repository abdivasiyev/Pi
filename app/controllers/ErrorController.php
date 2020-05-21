<?php

namespace app\controllers;

use app\core\renderers\Controller;
use Pi;

class ErrorController extends Controller
{
    public $layout = 'error';

    public function page404()
    {
        http_response_code(404);
        return $this->render();
    }
}