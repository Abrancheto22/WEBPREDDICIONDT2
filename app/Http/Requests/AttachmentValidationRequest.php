<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachmentValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'documentos_adjuntos' => 'nullable|array|max:5', // Máximo 5 archivos
            'documentos_adjuntos.*' => [
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,txt',
                'max:10240', // 10MB por archivo
                function ($attribute, $value, $fail) {
                    // Validación personalizada para tipos de archivo peligrosos
                    $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
                    $extension = strtolower($value->getClientOriginalExtension());
                    
                    if (in_array($extension, $dangerousExtensions)) {
                        $fail('El tipo de archivo ' . $extension . ' no está permitido por razones de seguridad.');
                    }
                    
                    // Validar el MIME type real del archivo
                    $allowedMimeTypes = [
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
                    
                    $fileMimeType = $value->getMimeType();
                    if (!in_array($fileMimeType, $allowedMimeTypes)) {
                        $fail('El tipo de archivo no es válido. MIME type detectado: ' . $fileMimeType);
                    }
                }
            ],
            'attachments' => 'nullable|array|max:5', // Para el formulario de edición
            'attachments.*' => [
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,txt',
                'max:10240', // 10MB por archivo
                function ($attribute, $value, $fail) {
                    // Misma validación personalizada
                    $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js'];
                    $extension = strtolower($value->getClientOriginalExtension());
                    
                    if (in_array($extension, $dangerousExtensions)) {
                        $fail('El tipo de archivo ' . $extension . ' no está permitido por razones de seguridad.');
                    }
                    
                    $allowedMimeTypes = [
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
                    
                    $fileMimeType = $value->getMimeType();
                    if (!in_array($fileMimeType, $allowedMimeTypes)) {
                        $fail('El tipo de archivo no es válido. MIME type detectado: ' . $fileMimeType);
                    }
                }
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'documentos_adjuntos.max' => 'No se pueden subir más de 5 archivos a la vez.',
            'documentos_adjuntos.*.file' => 'Cada elemento debe ser un archivo válido.',
            'documentos_adjuntos.*.mimes' => 'Los archivos deben ser de tipo: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF o TXT.',
            'documentos_adjuntos.*.max' => 'Cada archivo no puede ser mayor a 10MB.',
            'attachments.max' => 'No se pueden subir más de 5 archivos a la vez.',
            'attachments.*.file' => 'Cada elemento debe ser un archivo válido.',
            'attachments.*.mimes' => 'Los archivos deben ser de tipo: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF o TXT.',
            'attachments.*.max' => 'Cada archivo no puede ser mayor a 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'documentos_adjuntos' => 'documentos adjuntos',
            'documentos_adjuntos.*' => 'archivo adjunto',
            'attachments' => 'archivos adjuntos',
            'attachments.*' => 'archivo adjunto',
        ];
    }

    /**
     * Validar el tamaño total de todos los archivos
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $totalSize = 0;
            $maxTotalSize = 50 * 1024 * 1024; // 50MB total
            
            // Verificar documentos_adjuntos
            if ($this->hasFile('documentos_adjuntos')) {
                foreach ($this->file('documentos_adjuntos') as $file) {
                    $totalSize += $file->getSize();
                }
            }
            
            // Verificar attachments
            if ($this->hasFile('attachments')) {
                foreach ($this->file('attachments') as $file) {
                    $totalSize += $file->getSize();
                }
            }
            
            if ($totalSize > $maxTotalSize) {
                $validator->errors()->add('documentos_adjuntos', 'El tamaño total de todos los archivos no puede exceder 50MB.');
            }
        });
    }
}