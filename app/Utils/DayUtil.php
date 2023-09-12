<?php

namespace App\Utils;

use App\Traits\RequestAbleTrait;

class DayUtil
{
    use RequestAbleTrait;

    public function __construct(
        protected string $day
    ){}

    public function checkingDay(){
        $day = $this->day;
        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        return array_search($day,$days);
    }
}
