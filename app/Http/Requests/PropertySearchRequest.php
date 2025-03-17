<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertySearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'investmentSelect' => 'nullable|integer',
            'roomsSelect' => 'nullable|integer',
            'areaSelect' => 'nullable|string|regex:/^\d+-\d+$/',
            'typeSelect' => 'nullable|string',
        ];
    }
} 