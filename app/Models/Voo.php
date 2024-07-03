<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voo extends Model
{
    use HasFactory;

    protected $table = 'voos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'numero',
        'aeroporto_origem_id',
        'aeroporto_destino_id',
        'data_hora_partida',
        'voo_status',
    ];

    public static function generateUniqueFlightNumber()
    {
        do {
            $number = mt_rand(100000, 999999);
        } while (self::where('numero', $number)->exists());

        return $number;
    }

    public function aeroportoOrigem()
    {
        return $this->belongsTo(Aeroporto::class, 'aeroporto_origem_id');
    }

    public function aeroportoDestino()
    {
        return $this->belongsTo(Aeroporto::class, 'aeroporto_destino_id');
    }
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'voos_classes', 'voo_id', 'classe_id');
    }
    public function ticket()
    {
        return $this->hasmany(Ticket::class, 'voo_id', 'id');
    }
}
