<?php

namespace app\core\request;

use app\core\helpers\Html;

class Get
{

    public function __construct()
    {
        $this->getParams = $_GET;
    }

    public function get(string $key = null)
    {
        if (isset($this->getParams[$key]))
        {
            return Html::encode($this->getParams[$key]);
        }

        return $key === null ? Html::encode($this->getParams) : null;
    }

}