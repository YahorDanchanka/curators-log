<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('administrative_divisions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['region', 'district'])->default('region');
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table
                ->foreign('parent_id')
                ->references('id')
                ->on('administrative_divisions')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_divisions');
    }
};
