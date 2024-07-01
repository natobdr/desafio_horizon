<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoClasse extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_id',
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class, 'tipo_id', 'id');
    }
}
