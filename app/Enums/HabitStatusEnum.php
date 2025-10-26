<?php

namespace App\Enums;

enum HabitStatusEnum: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
}
