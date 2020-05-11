<?php

namespace app\orm;

use App;
use app\orm\AbstractDatabase;

class Database extends AbstractDatabase {

    public function __construct()
    {
        $config = App::$app->config['database'];

        parent::__construct($config);
    }
}