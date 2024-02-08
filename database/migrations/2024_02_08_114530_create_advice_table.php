<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advice', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('full_name')->nullable();
            $table->string('position')->nullable();
            $table
                ->foreignId('group_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advice');
    }
};
