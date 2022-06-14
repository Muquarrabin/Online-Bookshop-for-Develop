<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingAddressRequest extends FormRequest
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
            'shipping_name' => 'required',
            'mobile_no' =>'required',
            'address' => 'required',
            'city' => 'required',
            'post_code' => 'required',
            'area_id' => 'required|exists:shipping_charges,id',
        ];
    }
}
