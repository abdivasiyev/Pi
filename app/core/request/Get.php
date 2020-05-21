<?php

namespace app\core\request;

use app\core\helpers\Html;

class Get
{

    /**
     * @var array
     */
    public $get;

    /**
     * Get constructor.
     */
    public function __construct()
    {
        $this->get = Html::encode($_GET);
    }

}