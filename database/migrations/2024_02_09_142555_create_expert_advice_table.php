<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expert_advice', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->text('result')->nullable();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_advice');
    }
};
