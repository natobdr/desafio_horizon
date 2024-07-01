<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aeroporto extends Model
{
    use HasFactory;

    protected $table = 'aeroportos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo_iata',
        'nome',
        'cidade_id',
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function voosOrigem()
    {
        return $this->hasMany(Voo::class, 'aeroporto_origem_id', 'id');
    }

    public function voosDestino()
    {
        return $this->hasMany(Voo::class, 'aeroporto_destino_id', 'id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'voos_classe');
    }
}
