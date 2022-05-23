<?php

namespace App\Console\Commands;

use App\Http\Actions\GerarPrecoMedioDaCriptomoeda;
use App\Models\PrecoCriptomoeda;
use Illuminate\Console\Command;

class CheckAvgBigPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'c:checkAvgBigPrice {criptomoeda}';

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
            $this->warn('Uma criptomoeda deve ser informada!');
        } else {
            $this->newLine();
            $verificarPrecoMedioDeUmaCriptomoeda = new GerarPrecoMedioDaCriptomoeda();
            $precoMedioDaCriptomoeda = $verificarPrecoMedioDeUmaCriptomoeda($criptomoeda);

            $this->info("O preço médio da criptomoeda {$criptomoeda} é US$ {$precoMedioDaCriptomoeda}");

            $precoMaisRecenteDaCriptomoeda = (float) PrecoCriptomoeda::porSymbol($criptomoeda)->get()->last()->preco_lance;


            $precoMedioDaCriptomoedaMenosZeroCincoPorcento = $precoMedioDaCriptomoeda - ($precoMedioDaCriptomoeda * 0.005);

            $precoMaisRecenteEhMenorDoQueZeroCincoPorcentoDoPrecoMedio = ($precoMaisRecenteDaCriptomoeda < $precoMedioDaCriptomoedaMenosZeroCincoPorcento);

            if ($precoMaisRecenteEhMenorDoQueZeroCincoPorcentoDoPrecoMedio) {
                $this->newLine();
                $this->warn("O preço mais recente(US$ {$precoMaisRecenteDaCriptomoeda}) está menor do que 0.5% do que o preço médio(US$ {$precoMedioDaCriptomoeda})!");
            }
        }
        return 0;
    }
}
