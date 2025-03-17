<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingFormRequest extends FormRequest
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
            "cords" => '',
            "html" => '',
            "active" => "boolean",
            "name" => "required|string|max:255",
            "meta_title" => '',
            "meta_description" => '',
            "meta_robots" => '',
            "number" => "required|string|max:255",
            "area_range" => 'nullable',
            "rooms_range" => 'nullable',
            "price_range" => 'nullable',
            "content" => '',
            "investment_id" => "required|integer"
        ];
    }
}



