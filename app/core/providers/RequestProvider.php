<?php

namespace app\core\providers;

use app\core\request\Request;
use app\core\base\AbstractProvider;

class RequestProvider extends AbstractProvider
{

    public $name = 'request';

    public function init()
    {
        return new Request();
    }
}