<?php

namespace app\core\request;

use app\core\helpers\Html;

class Post
{

    public function __construct()
    {
        $this->postParams = $_POST;
    }

    public function get(string $key = null)
    {
        if (isset($this->postParams[$key]))
        {
            return Html::encode($this->postParams[$key]);
        }

        return $key === null ? Html::encode($this->postParams) : null;
    }
}