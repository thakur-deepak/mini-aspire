<?php

namespace App\Helpers;

class Helper
{
    public static function getConstant($string)
    {
        return config('constants.' . $string);
    }
}
