<?php

namespace app\request;

use app\helpers\Html;
use app\helpers\Converter;
use app\exceptions\UnknownPropertyException;

class Server {

    private $_data;

    public function __construct()
    {
        // $this->_data = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_ENCODED);
        $this->_data = $this->optimize(Html::encode($_SERVER));
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

    private function optimize($data)
    {
        $d = [];

        foreach($data as $key => $value) {
            $key = Converter::dashesToCamelCase($key, false);
            $d[$key] = $value;
        }

        return $d;
    }
}