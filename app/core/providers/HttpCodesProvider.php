<?php


namespace app\core\providers;


use app\core\base\AbstractProvider;
use app\core\http\HttpCodes;

class HttpCodesProvider extends AbstractProvider
{

    public $name = 'httpCodes';

    function init()
    {
        return new HttpCodes();
    }
}