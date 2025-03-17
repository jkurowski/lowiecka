<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClientPropertyFormRequest extends FormRequest
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
            'investment_id' => 'required|exists:investments,id',
            'property_id' => 'required|exists:properties,id',
            'status' => 'required|integer',
            'reservation_date' => 'required_if:status,2|nullable|date',
            'saled_at' => 'required_if:status,3|nullable|date',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400));
    }

    public function messages()
    {
        return [
            'investment_id.exists' => 'Wybierz <b>Inwestycję</b> z listy.',
            'property_id.exists' => 'Wybierz <b>Mieszkanie</b> z listy.',
            'saled_at.required_if' => 'Pole <b>Data sprzedaży</b> musi być uzupełnione.',
            'reservation_date.required_if' => 'Pole <b>Data zakończenia rezerwacji</b> musi być uzupełnione.',
        ];
    }
}
