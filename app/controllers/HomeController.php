<?php

namespace app\controllers;

use App;
use app\core\base\Controller;

class HomeController extends Controller
{

    public function index($slug = null)
    {
        return $this->render('home/index', ['name' => 'Asliddin']);
    }

}