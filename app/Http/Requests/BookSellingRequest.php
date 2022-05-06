<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookSellingRequest extends FormRequest
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
            'book_title'            => 'required',
            'book_description'      => 'required',
            'author_id'             => 'required',
            'category_id'           => 'required',
            'image_id'              => 'required',
            'asking_price'          => 'required|numeric',
            'selling_price'         => 'required|numeric',
            'seller_name'           => 'required',
            'seller_mobile'         => 'required',
            'seller_address'        => 'required',
            'seller_email'          => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'author_id.required'    => 'Author field required',
            'category_id.required'  => 'Category field required',
            'image_id.required'     => 'Image field required'
        ];
    }
}
