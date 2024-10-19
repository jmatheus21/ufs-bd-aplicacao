<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantem extends Model
{
    protected $table = 'mantem';

    protected $primaryKey = 'id';

    protected $fillable = [
        'mecanico_cpf',
        'aeronave_matricula',
        'horario',
        'status',
        'detalhes'
    ];

    // Definir relação com Aeronave
    public function aeronave()
    {
        return $this->belongsTo(Aeronave::class, 'aeronave_matricula', 'matricula');
    }

    // Definir relação com Mecanico
    public function mecanico()
    {
        return $this->belongsTo(Mecanico::class, 'mecanico_cpf', 'cpf');
    }
}