<?php

/**
 * Add your custom routes here.
 * For example:
 * 'site' => [
 *      'pattern' => '/(id:int)',
 *      'controller' => 'HomeController:index',
 *      'method' => 'GET'
 * ]
 * 
 * or
 * 
 * 'site' => ['/', 'index', 'get']
 */
return [
    'home' => ['/', 'index'],
    'news' => [
        'pattern' => '/(title:slug)',
        'controller' => 'HomeController:index'
    ],
];