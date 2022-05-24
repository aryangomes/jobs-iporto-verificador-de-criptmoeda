<?php

namespace Tests\Feature\Commands;

use App\Models\PrecoCriptomoeda;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaveBidPriceOnDataBaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 
     *
     * @test
     */
    public function comando_para_salvar_o_preco_da_criptomoeda_deve_ser_executado_com_sucesso()
    {
        $this->artisan('c:saveBidPriceOnDataBase')->assertSuccessful();
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_salvar_o_preco_da_criptomoeda_deve_ser_guardar_os_precos_no_banco_de_dados_com_sucesso()
    {
        $this->artisan('c:saveBidPriceOnDataBase')->assertSuccessful();

        $this->assertDatabaseCount('precos_criptomoeda', PrecoCriptomoeda::all()->count());
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_salvar_o_preco_da_criptomoeda_deve_ser_guardar_um_preco_de_uma_determinada_criptomoeda_no_banco_de_dados_com_sucesso()
    {

        $criptomoeda = 'BTCUSDT';
        $this->artisan("c:saveBidPriceOnDataBase {$criptomoeda}")->assertSuccessful();

        $precoCriptomoeda = PrecoCriptomoeda::porSymbol($criptomoeda)->first();

        $this->assertDatabaseHas('precos_criptomoeda', [
            "id" => $precoCriptomoeda->id,
            "criptomoeda" => $precoCriptomoeda->criptomoeda,
            "preco_lance" => $precoCriptomoeda->preco_lance,

        ]);
    }

    /**
     * 
     *
     * @test
     */
    public function comando_para_salvar_o_preco_da_criptomoeda_deve_informar_que_a_criptomoeda_informada_eh_invalida()
    {
        $criptomoeda = '123456';

        $this->artisan("c:saveBidPriceOnDataBase {$criptomoeda}")
            ->expectsOutput(__('comandos.criptomoeda_invalida', ['criptomoeda' => $criptomoeda]))
            ->assertExitCode(0);
    }
}
