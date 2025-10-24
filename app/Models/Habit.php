<?php

namespace App\Models;

use App\Enums\HabitStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habit extends Model
{
    protected $fillable = [
        'goal_id',
        'name',
        'description',
        'schedule_time',
        'repeat_days',
        'min_action',
        'min_time',
        'environment_design',
        'reward',
        'notes',
        'status',
    ];

    protected $casts = [
        'repeat_days' => 'array',
        'status' => HabitStatusEnum::class,
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }
}
