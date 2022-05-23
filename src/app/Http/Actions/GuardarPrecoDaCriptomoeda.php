<?php

namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuardarPrecoDaCriptomoeda
{

    public function __invoke(array $dadosDaCriptomoeda): PrecoCriptomoeda
    {
        try {
            DB::beginTransaction();

            $precoCriptomoeda = PrecoCriptomoeda::create($dadosDaCriptomoeda);

            DB::commit();

            return $precoCriptomoeda;
        } catch (\Exception $exception) {
            Log::error('GuardarPrecoDaCriptomoeda', [
                'mensagem' => $exception->getMessage()
            ]);
            DB::rollBack();
        }
    }
}
