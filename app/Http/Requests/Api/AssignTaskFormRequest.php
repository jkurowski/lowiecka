<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignTaskFormRequest extends FormRequest
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
    public function rules()
    {
        return [
            'start' => 'required|date|date_format:Y-m-d',
            'time' => 'nullable|date_format:H:i',
            'user_id' => 'nullable|integer|exists:users,id',
            'task_id' => 'nullable|integer|exists:board_tasks,id',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start.required' => 'Pole <b>data</b> jest wymagane',
            'start.date_format' => 'Zły format daty w polu <b>Data</b>',
            'user_id.integer' => 'Zły ID <b>Użytkownika</b>',
            'user_id.exists' => 'Wybrany <b>Użytkownik</b> nie istnieje',
        ];
    }
}
