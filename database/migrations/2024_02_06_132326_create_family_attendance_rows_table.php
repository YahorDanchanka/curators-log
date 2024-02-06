<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_attendance_rows', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('value');
            $table
                ->foreignId('family_attendance_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unique(['date', 'family_attendance_id', 'course_id'], 'f_a_r_d_f_a_id_c_id_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_attendance_rows');
    }
};
