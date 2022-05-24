<?php

namespace Tests\Feature\Commands;

use App\Http\Actions\GerarPrecoMedioDaCriptomoeda;
use App\Models\PrecoCriptomoeda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use RuntimeException;
use Tests\TestCase;

class CheckAvgBigPriceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 
     *
     * @test
     */
    public function comando_para_verificar_o_preco_medio_da_criptomoeda_deve_ser_executado_com_sucesso()
    {
        $criptomoeda = 'BTCUSDT';
        $this->artisan("c:checkAvgBigPrice {$criptomoeda}")->assertSuccessful();
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_verificar_o_preco_medio_da_criptomoeda_deve_exibir_mensagem_que_esta_faltando_ser_informada_a_criptomoeda()
    {
        $this->expectException(RuntimeException::class);
        $this->artisan('c:checkAvgBigPrice');
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_verificar_o_preco_medio_da_criptomoeda_deve_retornar_o_preco_medio_com_sucesso()
    {
        $criptomoeda = 'BTCUSDT';

        $this->artisan("c:saveBidPriceOnDataBase {$criptomoeda}")->assertSuccessful();

        $verificarPrecoMedioDeUmaCriptomoeda =
            new GerarPrecoMedioDaCriptomoeda();

        $precoMedioDaCriptomoeda = $verificarPrecoMedioDeUmaCriptomoeda($criptomoeda);


        $this->artisan("c:checkAvgBigPrice {$criptomoeda}")
            ->expectsOutput("O preço médio da criptomoeda {$criptomoeda} é US$ {$precoMedioDaCriptomoeda}")
            ->assertExitCode(0);
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_verificar_o_preco_medio_da_criptomoeda_deve_alertar_que_o_preco_atual_esta_menor_do_que_05_porcento_do_preco_medio_com_sucesso()
    {
        $criptomoeda = 'BTCUSDT';

        PrecoCriptomoeda::factory()->count(100)->create([
            'criptomoeda' => $criptomoeda
        ]);


        $this->artisan("c:saveBidPriceOnDataBase {$criptomoeda}")->assertSuccessful();

        $verificarPrecoMedioDeUmaCriptomoeda =
            new GerarPrecoMedioDaCriptomoeda();

        $precoMedioDaCriptomoeda = $verificarPrecoMedioDeUmaCriptomoeda($criptomoeda);

        $precoCriptomoeda = PrecoCriptomoeda::porSymbol($criptomoeda)->get()->last();

        $precoMaisRecenteDaCriptomoeda = (float) $precoCriptomoeda->preco_lance;

        $alertaDePrecoAtualMenorDoQuePrecoMedioCriptomoeda = "*     " . __('comandos.preco_atual_menor_que_preco_medio_criptomoeda', [
            'precoMaisRecenteDaCriptomoeda' => $precoMaisRecenteDaCriptomoeda,
            'precoMedioDaCriptomoeda' => $precoMedioDaCriptomoeda,
        ]) . "     *";

        $this->artisan("c:checkAvgBigPrice {$criptomoeda}")
            ->expectsOutput(__('comandos.preco_medio_criptomoeda', [
                'criptomoeda' => $criptomoeda,
                'precoMedioDaCriptomoeda' => $precoMedioDaCriptomoeda,
            ]))
            ->expectsOutput($alertaDePrecoAtualMenorDoQuePrecoMedioCriptomoeda)
            ->assertExitCode(0);
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_verificar_o_preco_medio_da_criptomoeda_deve_informar_que_a_criptomoeda_informada_eh_invalida()
    {
        $criptomoeda = '123456';

        $this->artisan("c:checkAvgBigPrice {$criptomoeda}")
            ->expectsOutput(__(
                'comandos.nao_ha_registros_criptomoeda',
                ['criptomoeda' => $criptomoeda]
            ))
            ->assertExitCode(0);
    }
}
