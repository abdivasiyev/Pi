<?php

/**
 * @param $arr
 */
function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

/**
 * @param $arr
 */
function varDump($arr)
{
    echo '<pre>';
    var_dump($arr);
    exit;
}