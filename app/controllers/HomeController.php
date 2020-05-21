<?php

namespace app\controllers;

use app\core\renderers\Controller;

class HomeController extends Controller
{

    public function index()
    {
        debug(\Pi::$app->config);
        return $this->render('home/index', ['name' => 'Asliddin']);
    }

}