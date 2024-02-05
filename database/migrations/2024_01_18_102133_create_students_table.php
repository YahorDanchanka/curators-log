<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->enum('sex', ['мужской', 'женский']);
            $table->date('birthday')->nullable();
            $table
                ->string('citizenship')
                ->nullable()
                ->default('Республика Беларусь');
            $table->string('home_phone')->nullable();
            $table->string('phone')->nullable();
            $table->string('educational_institution')->nullable();
            $table->string('social_conditions')->nullable();
            $table->string('hobbies')->nullable();
            $table->string('other_details')->nullable();
            $table->date('medical_certificate_date')->nullable();
            $table->string('health')->nullable();
            $table
                ->string('apprenticeship')
                ->nullable()
                ->default('Внебюджет');
            $table->string('image_url')->nullable();
            $table
                ->foreignId('address_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->unsignedBigInteger('study_address_id')->nullable();
            $table
                ->foreign('study_address_id')
                ->references('id')
                ->on('addresses')
                ->nullOnDelete();
            $table
                ->foreignId('passport_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('group_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
