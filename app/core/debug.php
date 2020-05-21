<?php

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
    exit;
}

function varDump($arr)
{
    echo '<pre>';
    var_dump($arr);
    exit;
}