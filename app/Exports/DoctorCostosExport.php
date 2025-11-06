<?php

namespace App\Exports;

use App\Models\Doctor;
use App\Models\Cita;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DoctorCostosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Doctor::select('iddoctor','nombre','apellido','sueldo')->get();
    }

    public function headings(): array
    {
        return [
            'Doctor',
            'Predicciones',
            'Tiempo Total',
            'Salario',
            'Costo por hora',
            'Costo Total',
            'COPG',
        ];
    }

    /**
     * @param \App\Models\Doctor $doctor
     */
    public function map($doctor): array
    {
        $citas = Cita::where('iddoctor', $doctor->iddoctor)->with('prediccion')->get();

        $predCount = 0;
        $totalTime = 0.0; // segundos
        foreach ($citas as $c) {
            if ($c->prediccion) {
                $predCount++;
                $t = $c->prediccion->timer;
                if (is_numeric($t)) {
                    $totalTime += (float) $t;
                } else if (is_string($t)) {
                    $num = preg_replace('/[^0-9.]/', '', $t);
                    $totalTime += (float) $num;
                }
            }
        }

        $salary = (float) ($doctor->sueldo ?? 0);
        $hourly = $salary / 192; // costo por hora
        $totalHours = $totalTime / 3600; // a horas
        $totalCost = $hourly * $totalHours;
        $copg = $predCount > 0 ? ($totalCost / $predCount) : 0;

        $seconds = (int) round($totalTime);
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        $timeFormatted = sprintf('%02d:%02d:%02d', $h, $m, $s);

        return [
            $doctor->nombre . ' ' . $doctor->apellido,
            $predCount,
            $timeFormatted,
            number_format($salary, 2, '.', ''),
            number_format($hourly, 2, '.', ''),
            number_format($totalCost, 2, '.', ''),
            number_format($copg, 2, '.', ''),
        ];
    }
}
