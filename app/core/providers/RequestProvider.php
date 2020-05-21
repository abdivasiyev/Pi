<?php

namespace app\core\providers;

use app\core\request\Request;
use app\core\base\AbstractProvider;

class RequestProvider extends AbstractProvider
{

    /**
     * @var string
     */
    public $name = 'request';

    /**
     * @return Request|mixed
     */
    public function init()
    {
        return new Request();
    }
}