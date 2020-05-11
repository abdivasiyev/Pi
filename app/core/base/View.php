<?php

namespace app\base;

use App;
use app\exceptions\FileNotFoundException;

class View 
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function render($file, $params = [])
    {
        if (file_exists('../app/views/' . $file . '.php'))
        {
            ob_start();
            extract($params);
            include '../app/views/' . $file . '.php';
            $content = ob_get_clean();

            return $content;
        } else {
            App::redirect('/home/error');
        }
    }
}