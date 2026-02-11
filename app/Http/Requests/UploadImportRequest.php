<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,xlsx',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Format file harus CSV atau XLSX.',
        ];
    }
}
