<?php

namespace app\core\request;

use app\core\helpers\Html;

class Server
{

    /**
     * @var array
     */
    public $server;

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->server = Html::encode($_SERVER);
    }
}