<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecoCriptomoeda extends Model
{
    use HasFactory;


    protected $table = 'precos_criptomoeda';

    protected $fillable = [
        'criptomoeda', 'preco_lance'
    ];

    public function scopePorSymbol($query, $criptomoeda)
    {
        return $query->where('criptomoeda', '=', $criptomoeda);
    }
}
