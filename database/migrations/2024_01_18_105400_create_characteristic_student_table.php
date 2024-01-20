<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('characteristic_student', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('characteristic_id')->constrained();
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unique(['student_id', 'characteristic_id', 'course_id'], 'c_s_s_id_c_id_c_id_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characteristic_student');
    }
};
