<?php

namespace app\controllers;

use Pi;
use app\core\base\Controller;

class HomeController extends Controller
{

    public function index($slug = null)
    {
        return $this->render('home/index', ['name' => 'Asliddin']);
    }

}