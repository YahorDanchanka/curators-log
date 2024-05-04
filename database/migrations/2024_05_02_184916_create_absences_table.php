<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->tinyInteger('reasonable_count')->unsigned();
            $table->tinyInteger('unreasonable_count')->unsigned();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unique(['date', 'student_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
