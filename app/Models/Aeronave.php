<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aeronave extends Model
{
    protected $table = 'aeronave';

    protected $primaryKey = 'matricula';

    public $incrementing = false;

    protected $keyType = 'string';

    // Campos que podem ser preenchidos via mass assignment
    protected $fillable = [
        'matricula',
        'condicao',
        'nivel_combustivel',
        'ultima_manutencao'
    ];
}