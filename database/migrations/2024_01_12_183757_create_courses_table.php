<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('number');
            $table->date('start_education');
            $table->date('end_education');
            $table->foreignId('curator_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->unique(['number', 'group_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
