<?php


namespace App\Http\Actions;

use Illuminate\Support\Facades\Http;

class RecuperarPrecosDaCriptomoedas
{
    private string $baseUrl = 'https://testnet.binancefuture.com';
    public function __invoke()
    {
        $recuperarPrecosDasCriptomoedasUrl = "{$this->baseUrl}/fapi/v1/ticker/price";

        $respostaRequisicao = Http::get($recuperarPrecosDasCriptomoedasUrl);

        return $respostaRequisicao->collect();
    }
}