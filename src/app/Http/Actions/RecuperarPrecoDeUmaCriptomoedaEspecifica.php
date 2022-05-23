<?php


namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;
use Illuminate\Support\Facades\Http;

class RecuperarPrecoDeUmaCriptomoedaEspecifica
{
    private string $baseUrl = 'https://testnet.binancefuture.com';
    public function __invoke(string $criptomoeda)
    {
        $recuperarPrecoDaCriptomoedaUrl = "{$this->baseUrl}/fapi/v1/ticker/price?symbol={$criptomoeda}";

        $respostaRequisicao = Http::get($recuperarPrecoDaCriptomoedaUrl);

        return $respostaRequisicao->collect();

        $dadosParaCadastrarPrecoCriptomoeda = [
            'criptomoeda' => $respostaRequisicao->collect()->get('symbol'),
            'preco_lance' => $respostaRequisicao->collect()->get('price'),
        ];

        $cadastrarNovoPrecoDaCriptomoeda = new GuardarPrecoDaCriptomoeda();

        $novoPrecoDaCriptomoeda = $cadastrarNovoPrecoDaCriptomoeda($dadosParaCadastrarPrecoCriptomoeda);

        return  $this->gerarMensagemDeRespostaParaComando($novoPrecoDaCriptomoeda);
    }

    public function gerarMensagemDeRespostaParaComando(PrecoCriptomoeda $precoCriptomoeda): string
    {

        $mensagemDeRespostaParaComando = "O preço mais recente de {$precoCriptomoeda->criptomoeda} é US$ {$precoCriptomoeda->preco_lance}";

        return $mensagemDeRespostaParaComando;
    }
}