<?php

namespace App\Exports;

use App\Models\Prediccion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrediccionesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Prediccion::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Id',
            'Tiempo Inicio',
            'Tiempo Parada',
            'Resultado',
            'Validar Predicción',
            'Tiempo de Análisis'
        ];
    }

    /**
     * @var Prediccion $prediccion
     */
    public function map($prediccion): array
    {
        return [
            $prediccion->idprediccion,
            $prediccion->timer_inicio,
            $prediccion->timer_parada,
            $prediccion->resultado,
            $prediccion->validar_prediccion,
            $prediccion->timer,
        ];
    }
}