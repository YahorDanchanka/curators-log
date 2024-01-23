<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expulsions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('initiator', ['student', 'college']);
            $table->text('reason')->nullable();
            $table
                ->foreignId('student_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expulsions');
    }
};
