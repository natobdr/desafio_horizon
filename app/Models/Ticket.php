<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'numero',
        'passageiro_id',
        'voo_id',
        'preco_total',
        'ticket_status',
    ];

    public static function generateUniqueTicketNumber()
    {
        do {
            $number = mt_rand(100000, 999999);
        } while (self::where('numero', $number)->exists());

        return $number;
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function passageiro()
    {
        return $this->belongsTo(Passageiro::class, 'passageiro_id', 'id');
    }

    public function voo()
    {
        return $this->belongsTo(Voo::class);
    }

    public function bagagem()
    {
        return $this->hasOne(Bagagem::class);
    }
}
