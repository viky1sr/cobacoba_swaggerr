<?php

namespace App\Traits;

trait RequestAbleTrait
{
    public static function IsRequestAble(...$arguments) {
        return new static(...$arguments);
    }
}
