<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string'],
            'lat_column' => ['required', 'string'],
            'lng_column' => ['required', 'string'],
            'address_column' => ['nullable', 'string'],
            'attribute_columns' => ['nullable', 'array'],
            'attribute_columns.*' => ['string'],
        ];
    }
}
