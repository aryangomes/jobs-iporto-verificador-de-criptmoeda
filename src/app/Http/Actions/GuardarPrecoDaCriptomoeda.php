<?php

namespace App\Http\Actions;

use App\Models\PrecoCriptomoeda;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuardarPrecoDaCriptomoeda
{

    public function __invoke(array $dadosDaCriptomoeda)
    {
        try {
            DB::beginTransaction();

            $precoCriptomoeda = PrecoCriptomoeda::create($dadosDaCriptomoeda);

            DB::commit();

            return $precoCriptomoeda;
        } catch (\Exception $exception) {
            Log::error('GuardarPrecoDaCriptomoeda', [
                'message' => $exception->getMessage()
            ]);
            DB::rollBack();
        }
    }
}
