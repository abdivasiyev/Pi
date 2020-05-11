<?php

namespace app\request;

use app\helpers\Html;
use app\exceptions\UnknownPropertyException;

class PostRequest {

    private $_data;

    public function __construct()
    {
        $this->_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_ENCODED);
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