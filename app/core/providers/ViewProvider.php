<?php

namespace app\core\providers;

use app\core\template\View;
use app\core\base\AbstractProvider;

class ViewProvider extends AbstractProvider
{

    public $name = 'view';

    public function init()
    {
        return new View();
    }
}