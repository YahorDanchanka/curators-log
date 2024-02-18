<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('residence');
            $table->string('street');
            $table->string('apartment_number');
            $table->unsignedBigInteger('region_id');
            $table
                ->foreign('region_id')
                ->references('id')
                ->on('administrative_divisions');
            $table->unsignedBigInteger('district_id');
            $table
                ->foreign('district_id')
                ->references('id')
                ->on('administrative_divisions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
