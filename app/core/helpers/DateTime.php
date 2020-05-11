<?php

namespace app\helpers;

class DateTime
{
    public static function dateTimeNow()
    {
        return date("Y-m-d H:m:s", time());
    }
}