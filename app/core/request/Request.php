<?php

namespace app\core\request;

use app\core\request\Get;
use app\core\base\AbstractRequest;

class Request extends AbstractRequest
{
    private $get;

    private $post;

    private $server;

    public function __construct()
    {
        $this->get  = new Get();
        $this->post = new Post();
        $this->server = new Server();
    }

    public function get(string $key = null)
    {
        return $this->get->get($key);
    }

    public function post(string $key = null)
    {
        return $this->post->get($key);
    }

    public function server(string $key = null)
    {
        return $this->server->get($key);
    }
}
