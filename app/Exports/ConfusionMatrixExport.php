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
        return ['TP', 'TN', 'FP', 'FN', 'F1 SCORE (%)', 'PRECISIÓN DEL MODELO (%)'];
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

        $precision = ($TP + $FP) > 0 ? $TP / ($TP + $FP) : 0.0;
        $recall    = ($TP + $FN) > 0 ? $TP / ($TP + $FN) : 0.0;
        $f1        = ($precision + $recall) > 0 ? (2 * $precision * $recall) / ($precision + $recall) : 0.0;
        $accuracy  = ($TP + $TN + $FP + $FN) > 0 ? ($TP + $TN) / ($TP + $TN + $FP + $FN) : 0.0;

        return [
            [
                $TP,
                $TN,
                $FP,
                $FN,
                round($f1 * 100, 2),
                round($accuracy * 100, 2),
            ],
        ];
    }
}
