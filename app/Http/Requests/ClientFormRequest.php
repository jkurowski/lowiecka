<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class ClientFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'lastname' => 'nullable',
            'user_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value === 0 || $value === '0') {
                        return;
                    }

                    if ($value !== null && !User::where('id', $value)->exists()) {
                        $fail('The selected user ID is invalid.');
                    }
                },
            ],
            'phone' => 'sometimes|required',
            'mail' => 'required',
            'mail2' => 'nullable',
            'phone2' => 'nullable',
            'source' => 'required|integer',
            'source_additional' => '',
            'fields' => 'array|nullable',
            'nip' => 'string|nullable|min:10|max:10',
            'pesel' => 'string|nullable|min:11|max:11',
            'id_type' => 'string|required',
            'id_number' => 'string|nullable',
            'id_issued_by' => 'string|nullable',
            'id_issued_date' => 'date|nullable',
            'is_company' => 'string|nullable',
            'company_name' => 'string|nullable',
            'regon' => 'string|nullable',
            'krs' => 'string|nullable',
            'address' => 'string|nullable',
            'exponent' => 'string|nullable',
            'city' => 'string|nullable',
            'post_code' => 'string|nullable',
            'street' => 'string|nullable',
            'house_number' => 'string|nullable',
            'apartment_number' => 'string|nullable',
            'martial_status' => 'string|nullable',
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
            'name.required' => 'To pole jest wymagane',
            'name.max.string' => 'Maksymalna ilość znaków: 100',
            'name.min.string' => 'Minimalna ilość znaków: 2'
        ];
    }
}
