<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagagem extends Model
{
    use HasFactory;

    protected $table = 'bagagens';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_id',
        'numero_identificacao',
    ];

    public static function generateUniqueBagNumber()
    {
        do {
            $number = mt_rand(100000, 999999);
        } while (self::where('numero_identificacao', $number)->exists());

        return $number;
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
