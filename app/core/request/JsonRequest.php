<?php

namespace app\request;

use app\helpers\Html;
use app\exceptions\UnknownPropertyException;

class JsonRequest {

    private $_data;

    public function __construct()
    {
        $this->_data = json_decode(file_get_contents('php://input'), true);
        $this->_data = Html::encode($this->_data);
    }

    public function __get($key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else {
            return null;
        }
    }

    public function all()
    {
        return $this->_data;
    }
}