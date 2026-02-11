<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $boundary = $this->input('boundary');
        if (is_string($boundary)) {
            $decoded = json_decode($boundary, true);
            if (is_array($decoded)) {
                $this->merge(['boundary' => $decoded]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'area_ha' => ['nullable', 'numeric', 'min:0'],
            'boundary' => ['required', 'array'],
            'boundary.type' => ['required', 'string', 'in:Polygon'],
            'boundary.coordinates' => ['required', 'array'],
            'attributes' => ['nullable', 'array'],
        ];
    }
}
