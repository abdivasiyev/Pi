<?php

namespace app\core\helpers;

use InvalidArgumentException;

class Html
{

    /**
     * @param $data
     * @param int $round
     * @return array
     */
    public static function encode($data, $round = 12)
    {
        if ($round <= 0)
        {
            throw new InvalidArgumentException("The information you enter is dangerous.");
        }
        
        if (is_array($data))
        {
            if (empty($data))
            {
                return $data;
            }

            foreach ($data as $key => $value)
            {
                if (is_array($value))
                {
                    $data[$key] = static::encode($value, $round-1);
                }
                else
                {
                    $data[$key] = htmlspecialchars($value);
                }
            }
        }

        return $data;
    }
}