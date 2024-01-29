<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_employment', function (Blueprint $table) {
            $table->id();
            $table->text('first_semester')->nullable();
            $table->text('second_semester')->nullable();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unique(['student_id', 'course_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_employment');
    }
};
