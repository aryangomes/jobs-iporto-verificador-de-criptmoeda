<?php


namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;

class GerarPrecoMedioDaCriptomoeda
{

    public function __invoke(string $criptomoeda): float
    {
        $precosDeUmaCriptomoeda = PrecoCriptomoeda::porSymbol($criptomoeda)->orderBy('created_at', 'DESC')->limit(100)->get();

        $precoMedioDaCriptomoeda =
            !is_null($precosDeUmaCriptomoeda->avg('preco_lance')) ? $precosDeUmaCriptomoeda->avg('preco_lance') : 0;

        return  $precoMedioDaCriptomoeda;
    }
}
