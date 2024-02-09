<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('individual_works', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('content');
            $table->text('result')->nullable();
            $table->enum('type', ['relative', 'student']);
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('individual_works');
    }
};
