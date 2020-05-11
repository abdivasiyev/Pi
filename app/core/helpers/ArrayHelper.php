<?php

namespace app\helpers;

class ArrayHelper {

    public static function isset($array, $key)
    {
        return isset($array[$key]);
    }

    public static function unset($array, $key)
    {
        if (ArrayHelper::isset($array, $key)) {
            unset($array[$key]);
        }
        
        $array = ArrayHelper::arrayFilter($array);
        $array = ArrayHelper::arrayValues($array);

        return $array;
    }

    public static function arrayFilter($array)
    {
        return array_filter($array);
    }

    public static function arrayValues($array)
    {
        return array_values($array);
    }

    public static function isMap(array $arr) {
        if (empty($arr)) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}