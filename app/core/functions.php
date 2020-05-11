<?php

function dd($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
    die();
}

function vd($arr)
{
    echo '<pre>';
    var_dump($arr);
    die();
}