<?php

namespace app\core\request;

use app\core\base\AbstractRequest;

class Request extends AbstractRequest
{
    /**
     * @var array|string
     */
    public $get;

    /**
     * @var array|string
     */
    public $post;

    /**
     * @var array|string
     */
    public $server;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get  = (new Get())->get;
        $this->post = (new Post())->post;
        $this->server = (new Server())->server;
    }
}
