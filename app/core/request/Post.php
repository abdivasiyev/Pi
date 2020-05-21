<?php

namespace app\core\request;

use app\core\helpers\Html;

class Post
{
    /**
     * @var array
     */
    public $post;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->post = Html::encode($_POST);
    }
}