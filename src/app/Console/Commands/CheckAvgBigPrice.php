<?php

namespace App\Console\Commands;

use App\Http\Actions\CalcularPrecoMedioDaCriptomoeda;
use App\Models\PrecoCriptomoeda;
use Exception;
use Illuminate\Console\Command;

class CheckAvgBigPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:checkAvgBigPrice {criptomoeda : Ticket ou símbolo da criptomoeda a ser verificada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica o preço médio de uma criptomoeda';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $criptomoeda = $this->argument('criptomoeda');

        if (is_null($criptomoeda)) {
            return 1;
        }

        $this->newLine();

        $precoCriptomoeda = PrecoCriptomoeda::porSymbol($criptomoeda)->get()->last();

        if (is_null($precoCriptomoeda)) {
            $this->error(
                __(
                    'comandos.nao_ha_registros_criptomoeda',
                    ['criptomoeda' => $criptomoeda]
                )
            );
        } else {
            $this->verificarPrecoMedioDaCriptomoeda($criptomoeda, $precoCriptomoeda);
        }

        return 0;
    }

    private function verificarPrecoMedioDaCriptomoeda($criptomoeda, $precoCriptomoeda)
    {
        $verificarPrecoMedioDeUmaCriptomoeda =
            new CalcularPrecoMedioDaCriptomoeda();

        $precoMedioDaCriptomoeda = $verificarPrecoMedioDeUmaCriptomoeda($criptomoeda);

        $this->info(__('comandos.preco_medio_criptomoeda', [
            'criptomoeda' => $criptomoeda,
            'precoMedioDaCriptomoeda' => $precoMedioDaCriptomoeda,
        ]));

        $precoMaisRecenteDaCriptomoeda = (float) $precoCriptomoeda->preco_lance;
        $precoMedioDaCriptomoedaMenosZeroCincoPorcento = $precoMedioDaCriptomoeda - ($precoMedioDaCriptomoeda * 0.005);

        $precoMaisRecenteEhMenorDoQueZeroCincoPorcentoDoPrecoMedio = ($precoMaisRecenteDaCriptomoeda < $precoMedioDaCriptomoedaMenosZeroCincoPorcento);

        if ($precoMaisRecenteEhMenorDoQueZeroCincoPorcentoDoPrecoMedio) {
            $this->newLine();
            $this->alert(__('comandos.preco_atual_menor_que_preco_medio_criptomoeda', [
                'precoMaisRecenteDaCriptomoeda' => $precoMaisRecenteDaCriptomoeda,
                'precoMedioDaCriptomoeda' => $precoMedioDaCriptomoeda,
            ]));
        }
    }
}
