<?php

use App\Enums\MonthEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PlanSectionEnum;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('content');
            $table->boolean('done')->default(false);
            $table->enum('section', array_map(fn(PlanSectionEnum $enum) => $enum->value, PlanSectionEnum::cases()));
            $table->enum('month', array_map(fn(MonthEnum $enum) => $enum->value, MonthEnum::cases()));
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
