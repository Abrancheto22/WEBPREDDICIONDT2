<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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
        $attachmentPaths = [];
        $attachmentNames = [];

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

        // Generar nombre único para el archivo
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . uniqid() . '.' . $extension;

        // Almacenar el archivo
        $path = $file->storeAs(self::ATTACHMENT_DIRECTORY, $fileName, 'public');

        if (!$path) {
            throw new \Exception('Error al almacenar el archivo: ' . $originalName);
        }

        Log::info('Archivo almacenado exitosamente', [
            'original_name' => $originalName,
            'stored_path' => $path,
            'file_size' => $file->getSize()
        ]);

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
            // Construir la ruta completa
            $fullPath = storage_path('app/public/' . $path);
            
            if (File::exists($fullPath)) {
                $result = File::delete($fullPath);
                
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
        $fullPath = storage_path('app/public/' . $path);
        return File::exists($fullPath);
    }

    /**
     * Obtener la ruta completa de un archivo
     *
     * @param string $path
     * @return string
     */
    public function getFullPath(string $path): string
    {
        return storage_path('app/public/' . $path);
    }

    /**
     * Obtener información de un archivo
     *
     * @param string $path
     * @return array|null
     */
    public function getFileInfo(string $path): ?array
    {
        $fullPath = $this->getFullPath($path);
        
        if (!File::exists($fullPath)) {
            return null;
        }

        return [
            'path' => $path,
            'full_path' => $fullPath,
            'size' => File::size($fullPath),
            'mime_type' => File::mimeType($fullPath),
            'extension' => File::extension($fullPath),
            'last_modified' => File::lastModified($fullPath)
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
        $attachmentsPath = storage_path('app/public/' . self::ATTACHMENT_DIRECTORY);
        
        if (!File::exists($attachmentsPath)) {
            return [
                'orphaned_files' => [],
                'deleted_count' => 0,
                'errors' => []
            ];
        }

        $allFiles = File::allFiles($attachmentsPath);
        $orphanedFiles = [];

        foreach ($allFiles as $file) {
            $relativePath = self::ATTACHMENT_DIRECTORY . '/' . $file->getRelativePathname();
            
            if (!in_array($relativePath, $referencedPaths)) {
                $orphanedFiles[] = $relativePath;
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
}