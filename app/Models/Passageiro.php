<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passageiro extends Model
{
    use HasFactory;

    protected $table = 'passageiros';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'email',
    ];

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'passageiro_id', 'id');
    }

    public function voo()
    {
        return $this->hasMany(Voo::class, 'id', 'passageiro_id');
    }
}
