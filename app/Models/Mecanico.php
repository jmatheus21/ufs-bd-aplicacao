<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mecanico extends Model
{
    protected $table = 'mecanico';

    protected $primaryKey = 'cpf';

    public $incrementing = false;


    // Campos que podem ser preenchidos via mass assignment
    protected $fillable = [
        'cpf',
        'primeiro_nome',
        'sobrenome', 
        'email',
        'telefone',
        'escolaridade',
        'data_nascimento',
        'salario',
        'admissao',
        'validade',
        'sexo',
        'endereco',
        'estado_civil',
        'raca',
        'licencas',
        'habilidades'
    ];
}
