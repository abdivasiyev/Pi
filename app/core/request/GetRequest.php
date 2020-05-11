<?php

namespace app\request;

use app\helpers\Html;
use app\exceptions\UnknownPropertyException;

class GetRequest {

    private $_data;

    public function __construct()
    {
        $this->_data = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
        // $this->_data = Html::encode($this->_data);
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