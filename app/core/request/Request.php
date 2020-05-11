<?php

namespace app\request;

use app\request\Server;
use app\request\GetRequest;
use app\request\JsonRequest;
use app\request\PostRequest;

class Request {

    private $_get;

    private $_post;

    private $_server;

    private $_json;

    public $isPost = false;

    public $isGet = true;

    public function __construct()
    {
        $this->_get = new GetRequest();
        $this->_post = new PostRequest();
        $this->_json = new JsonRequest();
        $this->_server = new Server();

        $this->isPost = $this->server('requestMethod') === 'POST';
        $this->isGet = $this->server('requestMethod') === 'GET';
    }

    public function get($key = null)
    {
        if ($key !== null) {
            return $this->_get->{$key};
        }

        return $this->_get->all();
    }

    public function post($key = null)
    {
        if ($key !== null) {
            return $this->_post->{$key};
        }

        return $this->_post->all();
    }

    public function server($key = null)
    {
        if ($key !== null) {
            return $this->_server->{$key};
        }

        return $this->_server->all();
    }

    public function json($key = null)
    {
        if ($key !== null) {
            return $this->_json->{$key};
        }

        return $this->_json->all();
    }

}