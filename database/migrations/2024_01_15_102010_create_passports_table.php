<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('series');
            $table->string('number');
            $table->string('district_department');
            $table->date('issue_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
