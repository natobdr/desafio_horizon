<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = [
        'tipo_id',
        'quantidade_assentos',
        'valor_assento',
    ];

    public function tipoClasse()
    {
        return $this->hasOne(TipoClasse::class,'id', 'tipo_id');
    }
    public function voos()
    {
        return $this->belongsToMany(Voo::class, 'voos_classes', 'classe_id', 'id');
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id', 'classe_id', );
    }
}
