<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relative_student', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table
                ->foreignId('relative_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unique(['relative_id', 'student_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relative_student');
    }
};
