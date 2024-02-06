<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('note')->nullable();
            $table
                ->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('relative_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_attendances');
    }
};
