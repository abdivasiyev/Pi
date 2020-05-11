<?php

namespace app\helpers;

class Html {

    public static function encode($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    $data[$key] = htmlspecialchars($value);
                } else {
                    $data[$key] = Html::encode($value);
                }
            }
        } else {
            return htmlspecialchars($data);
        }

        return $data;
    }
}