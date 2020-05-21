<?php

namespace app\core\request;

use app\core\helpers\Html;

class Server
{

    public function __construct()
    {
        $this->serverParams = $_SERVER;
    }

    public function get(string $key = null)
    {
        if (isset($this->serverParams[$key]))
        {
            return Html::encode($this->serverParams[$key]);
        }

        return $key === null ? Html::encode($this->serverParams) : null;
    }
}