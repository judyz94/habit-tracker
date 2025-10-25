<?php

use App\Enums\HabitStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->time('schedule_time')->nullable();
            $table->json('repeat_days')->nullable();
            $table->string('min_action')->nullable();
            $table->integer('min_time')->nullable();
            $table->text('environment_design')->nullable();
            $table->text('reward')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', array_column(HabitStatusEnum::cases(), 'value'))
                ->default(HabitStatusEnum::Active->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
