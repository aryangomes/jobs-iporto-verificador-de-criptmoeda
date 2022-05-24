<?php


namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class RecuperarPrecoDeUmaCriptomoedaEspecifica
{
    public function __invoke(string $criptomoeda): Collection|null
    {
        $baseUrl = config('app.url_api_binance');

        $recuperarPrecoDaCriptomoedaUrl = "{$baseUrl}/fapi/v1/ticker/price?symbol={$criptomoeda}";

        $respostaRequisicao = Http::get($recuperarPrecoDaCriptomoedaUrl);

        if ($respostaRequisicao->failed()) {
            return null;
        }

        return $respostaRequisicao->collect();
    }

    public function gerarMensagemDeRespostaParaComando(PrecoCriptomoeda $precoCriptomoeda): string
    {

        $mensagemDeRespostaParaComando = "O preço mais recente de {$precoCriptomoeda->criptomoeda} é US$ {$precoCriptomoeda->preco_lance}";

        return $mensagemDeRespostaParaComando;
    }
}
