<?php

namespace app\core\providers;

use app\core\renderers\View;
use app\core\base\AbstractProvider;

class ViewProvider extends AbstractProvider
{

    /**
     * @var string
     */
    public $name = 'view';

    /**
     * @return View|mixed
     */
    public function init()
    {
        return new View();
    }
}