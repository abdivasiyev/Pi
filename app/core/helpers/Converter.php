<?php

namespace app\helpers;

class Converter {

    public static function arrayToObject($array)
    {
        if (!empty($array)) {
            return (object)$array;
        }

        return null;
    }

    public static function objectToArray($object)
    {
        if ($object !== null) {
            return (array)$object;
        }

        return [];
    }

    public static function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {
        $string = strtolower($string);
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    public static function camelCaseToDashes($string)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $string));
    }

    public static function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $string));
    }

    public static function strToInt($value)
    {
        return (int)$value;
    }

    public static function strToFloat($value)
    {
        return (float)$value;
    }

}