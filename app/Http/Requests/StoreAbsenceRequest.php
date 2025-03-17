<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenceRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'Pracownik',
            'start_date' => 'Data rozpoczęcia',
            'end_date' => 'Data zakończenia',
            'reason' => 'Powód',
        ];
    }
}
