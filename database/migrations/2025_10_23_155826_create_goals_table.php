<?php

use App\Enums\GoalStatusEnum;
use App\Enums\GoalTypeEnum;
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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', array_column(GoalTypeEnum::cases(), 'value'))
                ->default(GoalTypeEnum::Weekly->value);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', array_column(GoalStatusEnum::cases(), 'value'))
                ->default(GoalStatusEnum::Active->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
