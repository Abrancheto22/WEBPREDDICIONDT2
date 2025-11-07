<?php

namespace App\Exports;

use App\Models\Prediccion;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConfusionMatrixExport implements FromArray, WithHeadings
{
    protected float $threshold;

    public function __construct(float $threshold = 0.35)
    {
        $this->threshold = $threshold;
    }

    public function headings(): array
    {
        return ['Variable', 'Cantidad', 'Umbral'];
    }

    public function array(): array
    {
        $TP = 0; $TN = 0; $FP = 0; $FN = 0;
        $predicciones = Prediccion::select('resultado', 'validar_prediccion')->get();
        foreach ($predicciones as $p) {
            if ($p->validar_prediccion === null) { continue; }
            $predictedPositive = ((float)$p->resultado) > $this->threshold;
            $actualPositive = (int)$p->validar_prediccion === 1;
            if ($predictedPositive && $actualPositive) { $TP++; }
            elseif (!$predictedPositive && !$actualPositive) { $TN++; }
            elseif ($predictedPositive && !$actualPositive) { $FP++; }
            else { $FN++; }
        }

        return [
            ['TP', $TP, $this->threshold],
            ['TN', $TN, $this->threshold],
            ['FP', $FP, $this->threshold],
            ['FN', $FN, $this->threshold],
        ];
    }
}
