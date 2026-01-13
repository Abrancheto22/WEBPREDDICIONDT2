<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportToSupabase extends Command
{
    protected $signature = 'export:supabase';
    protected $description = 'Exporta datos de SQL Server a un script SQL compatible con PostgreSQL (Supabase)';

    public function handle()
    {
        $this->info('Iniciando exportación de datos para Supabase...');

        $tables = [
            'rols' => 'idrol',
            'users' => 'id',
            'doctor' => 'iddoctor',
            'efermera' => 'idenfermera',
            'paciente' => 'idpaciente',
            'cita' => 'idcita',
            'triaje' => 'idtriaje',
            'prediccion' => 'idprediccion',
        ];

        $outputFile = database_path('supabase_data.sql');
        file_put_contents($outputFile, "-- Script de Datos generado automáticamente\n\n");

        foreach ($tables as $table => $pk) {
            $this->info("Exportando tabla: $table");
            
            // Usar conexión sqlsrv explícitamente
            $rows = DB::connection('sqlsrv')->table($table)->get();
            
            if ($rows->isEmpty()) {
                continue;
            }

            $sql = "-- Datos para $table\n";
            $sql .= "INSERT INTO \"$table\" (";
            
            // Obtener columnas del primer registro
            $firstRow = (array) $rows->first();
            $columns = array_keys($firstRow);
            
            // Escapar nombres de columnas para Postgres (ej. "DNI", "BMI")
            $escapedColumns = array_map(function($col) {
                return '"' . $col . '"';
            }, $columns);
            
            $sql .= implode(', ', $escapedColumns) . ") VALUES \n";
            
            $values = [];
            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $rowValues = [];
                
                foreach ($rowArray as $col => $val) {
                    if (is_null($val)) {
                        $rowValues[] = 'NULL';
                    } elseif (is_bool($val)) {
                        $rowValues[] = $val ? 'TRUE' : 'FALSE';
                    } elseif (is_numeric($val)) {
                        $rowValues[] = $val;
                    } else {
                        // Escapar comillas simples para SQL
                        $escapedVal = str_replace("'", "''", $val);
                        // Reemplazar caracteres nulos si los hubiera
                        $escapedVal = str_replace("\0", "", $escapedVal);
                        $rowValues[] = "'" . $escapedVal . "'";
                    }
                }
                
                $values[] = "(" . implode(', ', $rowValues) . ")";
            }
            
            $sql .= implode(",\n", $values) . ";\n\n";
            
            // Ajustar la secuencia de la clave primaria
            $maxId = DB::connection('sqlsrv')->table($table)->max($pk);
            if ($maxId) {
                $sql .= "SELECT setval(pg_get_serial_sequence('\"$table\"', '$pk'), $maxId);\n\n";
            }
            
            file_put_contents($outputFile, $sql, FILE_APPEND);
        }

        $this->info("Exportación completada. Archivo generado: $outputFile");
        $this->info("Por favor, ejecuta el contenido de este archivo en el Editor SQL de Supabase DESPUÉS de ejecutar 'supabase_schema.sql'.");
    }
}
