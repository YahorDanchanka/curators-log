<?php

use App\Enums\MonthEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->float('hours_per_week');
            $table->float('hours_saturday')->nullable();
            $table->enum('month', array_map(fn(MonthEnum $enum) => $enum->value, MonthEnum::cases()));
            $table->enum('week', [1, 2, 3, 4]);
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
