<?php

namespace App\Http\Requests;

use App\Rules\ReCaptchaV3;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactFormRequest extends FormRequest
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
            'name' => 'required',
            'lastname' => '',
            'email' => 'required|email:rfc',
            'message' => 'required',
            'phone' => 'required',
            //'g-recaptcha-response' => ['required', new ReCaptchaV3()]
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
            'name.required' => 'To pole jest wymagane',
            'lastname.required' => 'To pole jest wymagane',
            'email.required' => 'To pole jest wymagane',
            'email.email' => 'Nieprawidłowy adres e-mail',
            'message.required' => 'To pole jest wymagane',
            'phone.required' => 'To pole jest wymagane'
        ];
    }
}
