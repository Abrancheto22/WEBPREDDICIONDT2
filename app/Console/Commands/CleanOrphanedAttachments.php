<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prediccion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CleanOrphanedAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attachments:clean {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean orphaned attachment files that are not referenced in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('Iniciando limpieza de archivos adjuntos huérfanos...');
        
        // Obtener todos los paths de archivos adjuntos de la base de datos
        $referencedPaths = [];
        $predicciones = Prediccion::whereNotNull('attachment_paths')->get();
        
        foreach ($predicciones as $prediccion) {
            if ($prediccion->attachment_paths && is_array($prediccion->attachment_paths)) {
                $referencedPaths = array_merge($referencedPaths, $prediccion->attachment_paths);
            }
        }
        
        $referencedPaths = array_unique($referencedPaths);
        $this->info('Archivos referenciados en la base de datos: ' . count($referencedPaths));
        
        // Obtener todos los archivos en el directorio de attachments
        $attachmentsPath = storage_path('app/attachments');
        
        if (!File::exists($attachmentsPath)) {
            $this->info('El directorio de attachments no existe.');
            return 0;
        }
        
        $allFiles = File::allFiles($attachmentsPath);
        $orphanedFiles = [];
        
        foreach ($allFiles as $file) {
            $relativePath = 'attachments/' . $file->getRelativePathname();
            
            if (!in_array($relativePath, $referencedPaths)) {
                $orphanedFiles[] = $file->getPathname();
            }
        }
        
        $this->info('Archivos huérfanos encontrados: ' . count($orphanedFiles));
        
        if (empty($orphanedFiles)) {
            $this->info('No se encontraron archivos huérfanos.');
            return 0;
        }
        
        if ($isDryRun) {
            $this->warn('MODO DRY-RUN: Los siguientes archivos serían eliminados:');
            foreach ($orphanedFiles as $file) {
                $this->line('- ' . $file);
            }
            $this->info('Total de archivos que serían eliminados: ' . count($orphanedFiles));
            $this->warn('Para eliminar realmente los archivos, ejecute el comando sin --dry-run');
            return 0;
        }
        
        // Confirmar eliminación
        if (!$this->confirm('¿Está seguro de que desea eliminar ' . count($orphanedFiles) . ' archivos huérfanos?')) {
            $this->info('Operación cancelada.');
            return 0;
        }
        
        // Eliminar archivos huérfanos
        $deletedCount = 0;
        $errorCount = 0;
        
        foreach ($orphanedFiles as $file) {
            try {
                if (File::delete($file)) {
                    $deletedCount++;
                    $this->line('Eliminado: ' . $file);
                } else {
                    $errorCount++;
                    $this->error('Error al eliminar: ' . $file);
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error('Error al eliminar ' . $file . ': ' . $e->getMessage());
            }
        }
        
        $this->info("Limpieza completada:");
        $this->info("- Archivos eliminados: {$deletedCount}");
        if ($errorCount > 0) {
            $this->warn("- Errores: {$errorCount}");
        }
        
        // Limpiar directorios vacíos
        $this->cleanEmptyDirectories($attachmentsPath);
        
        return 0;
    }
    
    /**
     * Limpiar directorios vacíos recursivamente
     */
    private function cleanEmptyDirectories($path)
    {
        if (!File::isDirectory($path)) {
            return;
        }
        
        $directories = File::directories($path);
        
        foreach ($directories as $directory) {
            $this->cleanEmptyDirectories($directory);
            
            // Si el directorio está vacío después de la limpieza recursiva, eliminarlo
            if (count(File::allFiles($directory)) === 0 && count(File::directories($directory)) === 0) {
                try {
                    File::deleteDirectory($directory);
                    $this->line('Directorio vacío eliminado: ' . $directory);
                } catch (\Exception $e) {
                    $this->error('Error al eliminar directorio ' . $directory . ': ' . $e->getMessage());
                }
            }
        }
    }
}