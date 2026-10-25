<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AttachmentService
{
    /**
     * Directorio base para almacenar archivos adjuntos
     */
    const ATTACHMENT_DIRECTORY = 'attachments';

    /**
     * Tamaño máximo permitido por archivo (en bytes)
     */
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

    /**
     * Tamaño máximo total permitido (en bytes)
     */
    const MAX_TOTAL_SIZE = 50 * 1024 * 1024; // 50MB

    /** Número máximo de archivos por operación */
    const MAX_FILES = 5;

    /**
     * Extensiones de archivo permitidas
     */
    const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'txt'];

    /**
     * MIME types permitidos
     */
    const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'image/gif',
        'text/plain'
    ];

    /**
     * Extensiones peligrosas no permitidas
     */
    const DANGEROUS_EXTENSIONS = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'php', 'asp', 'jsp'];

    /**
     * Almacenar múltiples archivos adjuntos
     *
     * @param array $files Array de UploadedFile
     * @return array ['paths' => [], 'names' => []]
     * @throws \Exception
     */
    public function storeMultipleFiles(array $files): array
    {
        if (count($files) > self::MAX_FILES) {
            throw new \Exception('No se pueden almacenar más de ' . self::MAX_FILES . ' archivos por operación.');
        }

        $attachmentPaths = [];
        $attachmentNames = [];

        $this->validateTotalSize($files);

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $this->validateFile($file);
                
                $result = $this->storeFile($file);
                $attachmentPaths[] = $result['path'];
                $attachmentNames[] = $result['name'];
            }
        }

        return [
            'paths' => $attachmentPaths,
            'names' => $attachmentNames
        ];
    }

    /**
     * Almacenar un archivo individual
     *
     * @param UploadedFile $file
     * @return array ['path' => string, 'name' => string]
     * @throws \Exception
     */
    public function storeFile(UploadedFile $file): array
    {
        $this->validateFile($file);

        // Guardar con un nombre aleatorio para evitar colisiones y rutas aportadas por el cliente.
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::random(40) . '.' . strtolower($extension);

        // Los adjuntos clínicos nunca deben escribirse en el disco público.
        $path = $file->storeAs(self::ATTACHMENT_DIRECTORY, $fileName, $this->diskName());

        if (!$path) {
            throw new \Exception('No se pudo almacenar el archivo adjunto.');
        }

        Log::info('Archivo adjunto almacenado en disco privado.', ['path' => $path]);

        return [
            'path' => $path,
            'name' => $originalName
        ];
    }

    /**
     * Validar un archivo individual
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    public function validateFile(UploadedFile $file): void
    {
        // Validar tamaño
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('El archivo ' . $file->getClientOriginalName() . ' excede el tamaño máximo permitido de ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB');
        }

        // Validar extensión
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception('La extensión .' . $extension . ' no está permitida. Extensiones permitidas: ' . implode(', ', self::ALLOWED_EXTENSIONS));
        }

        // Validar extensiones peligrosas
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            throw new \Exception('El tipo de archivo .' . $extension . ' no está permitido por razones de seguridad');
        }

        // Validar MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw new \Exception('El tipo de archivo no es válido. MIME type detectado: ' . $mimeType);
        }
    }

    /**
     * Validar el tamaño total de múltiples archivos
     *
     * @param array $files
     * @throws \Exception
     */
    public function validateTotalSize(array $files): void
    {
        $totalSize = 0;
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $totalSize += $file->getSize();
            }
        }

        if ($totalSize > self::MAX_TOTAL_SIZE) {
            throw new \Exception('El tamaño total de todos los archivos (' . round($totalSize / 1024 / 1024, 2) . 'MB) excede el límite permitido de ' . (self::MAX_TOTAL_SIZE / 1024 / 1024) . 'MB');
        }
    }

    /**
     * Eliminar archivos por sus rutas
     *
     * @param array $paths
     * @return array ['deleted' => int, 'errors' => array]
     */
    public function deleteFiles(array $paths): array
    {
        $deleted = 0;
        $errors = [];

        foreach ($paths as $path) {
            try {
                if ($this->deleteFile($path)) {
                    $deleted++;
                } else {
                    $errors[] = "No se pudo eliminar el archivo: $path";
                }
            } catch (\Exception $e) {
                $errors[] = "Error al eliminar $path: " . $e->getMessage();
            }
        }

        return [
            'deleted' => $deleted,
            'errors' => $errors
        ];
    }

    /**
     * Eliminar un archivo individual
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            $disk = $this->disk();

            if ($disk->exists($path)) {
                $result = $disk->delete($path);
                
                if ($result) {
                    Log::info('Archivo eliminado exitosamente: ' . $path);
                } else {
                    Log::warning('No se pudo eliminar el archivo: ' . $path);
                }
                
                return $result;
            } else {
                Log::warning('Archivo no encontrado para eliminar: ' . $path);
                return true; // Consideramos exitoso si el archivo ya no existe
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar archivo: ' . $path . ' - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verificar si un archivo existe
     *
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        return $this->disk()->exists($path);
    }

    /**
     * Obtener la ruta completa de un archivo
     *
     * @param string $path
     * @return string
     */
    public function getFullPath(string $path): string
    {
        if (config('filesystems.disks.' . $this->diskName() . '.driver') !== 'local') {
            throw new \LogicException('Un disco remoto no tiene una ruta absoluta local.');
        }

        return $this->disk()->path($path);
    }

    /**
     * Obtener información de un archivo
     *
     * @param string $path
     * @return array|null
     */
    public function getFileInfo(string $path): ?array
    {
        $disk = $this->disk();
        if (!$disk->exists($path)) {
            return null;
        }

        $fullPath = config('filesystems.disks.' . $this->diskName() . '.driver') === 'local'
            ? $this->getFullPath($path)
            : null;

        return [
            'path' => $path,
            'full_path' => $fullPath,
            'size' => $disk->size($path),
            'mime_type' => $disk->mimeType($path),
            'extension' => pathinfo($path, PATHINFO_EXTENSION),
            'last_modified' => $disk->lastModified($path)
        ];
    }

    /**
     * Limpiar archivos huérfanos (no referenciados en la base de datos)
     *
     * @param array $referencedPaths Paths que están siendo utilizados
     * @param bool $dryRun Si es true, solo retorna los archivos que serían eliminados
     * @return array
     */
    public function cleanOrphanedFiles(array $referencedPaths, bool $dryRun = false): array
    {
        $disk = $this->disk();
        $allFiles = $disk->allFiles(self::ATTACHMENT_DIRECTORY);
        $orphanedFiles = [];

        foreach ($allFiles as $path) {
            if (!in_array($path, $referencedPaths, true)) {
                $orphanedFiles[] = $path;
            }
        }

        if ($dryRun) {
            return [
                'orphaned_files' => $orphanedFiles,
                'deleted_count' => 0,
                'errors' => []
            ];
        }

        // Eliminar archivos huérfanos
        $result = $this->deleteFiles($orphanedFiles);
        
        return [
            'orphaned_files' => $orphanedFiles,
            'deleted_count' => $result['deleted'],
            'errors' => $result['errors']
        ];
    }

    private function diskName(): string
    {
        return (string) config('filesystems.attachments_disk', 'local');
    }

    private function disk()
    {
        return Storage::disk($this->diskName());
    }
}
