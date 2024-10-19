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
        Schema::create('mantem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mecanico_cpf');
            $table->string('aeronave_matricula', 10);
            $table->dateTime('horario');
            $table->string('status', 15);
            $table->string('detalhes', 50);
            $table->timestamps();

            $table->foreign('aeronave_matricula')->references('matricula')->on('aeronave')->onDelete('cascade');
            $table->foreign('mecanico_cpf')->references('cpf')->on('mecanico')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantem');
    }
};