<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aeronave', function (Blueprint $table) {
            $table->string('matricula', 10)->unique();
            $table->string('condicao', 20);
            $table->string('nivel_combustivel', 15);
            $table->date('ultima_manutencao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aeronave');
    }
};