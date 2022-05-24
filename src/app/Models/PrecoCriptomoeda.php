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

    /**
     * Escopo local que filtra as criptomoedas dado o "symbol" (símbolo ou ticket) 
     * da criptomoeda
     * @param mixed $query
     * @param string $criptomoeda
     * @return mixed
     */
    public function scopePorSymbol($query, string $criptomoeda)
    {
        return $query->where('criptomoeda', '=', $criptomoeda);
    }
}
