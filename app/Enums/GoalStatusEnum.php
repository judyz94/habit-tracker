<?php

namespace App\Enums;

enum GoalStatusEnum: string
{
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';
}
