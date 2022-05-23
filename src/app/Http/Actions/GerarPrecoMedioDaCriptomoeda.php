<?php


namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;
use Ramsey\Uuid\Type\Decimal;

class GerarPrecoMedioDaCriptomoeda
{

    public function __invoke(string $criptomoeda): float
    {
        $precosDeUmaCriptomoeda = PrecoCriptomoeda::porSymbol($criptomoeda)->orderBy('created_at', 'DESC')->limit(100)->get();

        $precoMedioDaCriptomoeda = $precosDeUmaCriptomoeda->avg('preco_lance');

        return  $precoMedioDaCriptomoeda;
    }
}
