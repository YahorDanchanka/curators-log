<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->enum('sex', ['мужской', 'женский']);
            $table->date('birthday')->nullable();
            $table->string('job')->nullable();
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->string('educational_institution')->nullable();
            $table
                ->foreignId('address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatives');
    }
};
