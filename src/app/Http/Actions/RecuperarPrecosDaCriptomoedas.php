<?php


namespace App\Http\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class RecuperarPrecosDaCriptomoedas
{
    public function __invoke(): Collection
    {
        $baseUrl = config('app.url_api_binance');
        $recuperarPrecosDasCriptomoedasUrl = "{$baseUrl}/fapi/v1/ticker/price";

        $respostaRequisicao = Http::get($recuperarPrecosDasCriptomoedasUrl);

        return $respostaRequisicao->collect();
    }
}
