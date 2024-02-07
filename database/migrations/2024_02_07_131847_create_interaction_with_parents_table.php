<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interaction_with_parents', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('content');
            $table->text('result')->nullable();
            $table
                ->foreignId('group_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interaction_with_parents');
    }
};
