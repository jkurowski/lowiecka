<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PopupFormRequest extends FormRequest
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
            'popup_status' => 'boolean',
            'popup_mode' => 'boolean',
            'popup_timeout' => 'nullable|integer|min:0',
            'popup_text' => 'nullable|string',
            'popup_from' => 'nullable|date',
            'popup_until' => 'nullable|date|after_or_equal:popup_from',
        ];
    }
}
