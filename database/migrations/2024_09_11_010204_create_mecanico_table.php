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
        Schema::create('mecanico', function (Blueprint $table) {
            $table->unsignedBigInteger('cpf')->unique();
            $table->string('primeiro_nome', 30);
            $table->string('sobrenome', 60);
            $table->string('email');
            $table->string('telefone');
            $table->string('escolaridade', 30);
            $table->date('data_nascimento');
            $table->double('salario');
            $table->date('admissao');
            $table->date('validade');
            $table->char('sexo', 1);
            $table->string('endereco');
            $table->string('estado_civil', 20);
            $table->string('raca', 20);
            $table->string('licencas');
            $table->string('habilidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mecanico');
    }
};