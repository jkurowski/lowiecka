<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ZeroOrExists;
use Illuminate\Validation\Rule;

class PropertyFormRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->filled('window') && is_array($this->input('window'))) {
            $this->merge([
                'window' => implode(',', $this->input('window')) // Convert array to string
            ]);
        } else {
            $this->merge(['window' => null]);
        }

        $this->merge([
            'investment_id' => $this->route('investment')->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'investment_id' => [
                'required',
                'integer',
                Rule::exists('investments', 'id'), // Check if investment with the specified id exists
            ],
            'building_id' => 'nullable|integer',
            'floor_id' => 'nullable|integer',
            'storey' => 'nullable|integer',
            'storey_type' => 'nullable|integer',
            'storey_property' => 'nullable|integer',
            'status' => 'required',
            'name' => 'required|string|max:255',
            'name_list' => 'string|max:255',
            'number' => 'required|string|max:255',
            'number_order' => 'integer',
            'type' => 'required|integer',
            'rooms' => 'required|integer',
            'window' => 'sometimes|nullable',
            'area' => '',
            'area_search' => '',
            'area_sales' => '',
            'garden_area' => '',
            'balcony_area' => '',
            'balcony_area_2' => '',
            'terrace_area' => '',
            'loggia_area' => '',
            'plot_area' => '',
            'parking_space' => '',
            'garage' => '',
            'price' => '',
            'price_brutto' => ['nullable', 'regex:/^\d+(\.\d{1,2})?$/'],
            'vat' => '',
            'bank_account' => '',
            'cords' => '',
            'html' => '',
            'meta_title' => '',
            'meta_description' => '',
            'active' => 'boolean',
            'highlighted' => 'boolean',
            'promotion_end_date' => 'nullable|date|after:now',
            'promotion_price' => 'nullable',
            'client_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value != 0) {
                        $exists = Client::where('id', $value)->exists();
                        if (!$exists) {
                            $fail('The selected client does not exist.');
                        }
                    }
                },
            ],
            'saled_at' => 'nullable|date',
            'reservation_until' => 'nullable|date|after_or_equal:saled_at',

            'price-component-type'     => 'array',
            'price-component-type.*'   => 'required|exists:property_price_components,id',

            'price-component-category'   => 'array',
            'price-component-category.*' => 'required|in:1,2,3',

            'price-component-value'     => 'array',
            'price-component-value.*'   => 'nullable',

            'price-component-m2-value'     => 'array',
            'price-component-m2-value.*'   => 'nullable'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'client_id.exists' => 'The selected client does not exist.',
            'saled_at.date' => 'The saled at must be a valid date.',
            'reservation_until.date' => 'The reservation until must be a valid date.',
            'reservation_until.after_or_equal' => 'The reservation until must be a date after or equal to the saled at date.',
        ];
    }
}
