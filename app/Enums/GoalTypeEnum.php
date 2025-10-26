<?php

namespace App\Enums;

enum GoalTypeEnum: string
{
    case Annual = 'annual';
    case Monthly = 'monthly';
    case Weekly = 'weekly';
}
