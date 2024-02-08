<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_achievements', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->enum('semester', [1, 2]);
            $table
                ->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_achievements');
    }
};
