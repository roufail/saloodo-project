<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules =  [
            'title'           => 'required|min:3',
            'description'     => 'required|min:10',
            'price'           => 'required|numeric',
            'discount_amount' => 'required_with:discount_type:in:percentage,amount',
            'discount_type'   => 'required_with:discount_amount',
            'bundle'          => 'in:true,True,False,false',
            'childs'          => 'required_if:bundle,true,True',
            'childs.*'        => 'exists:products,id',

        ];

        if (request()->method() == 'POST') {
            $rules = array_merge($rules,[
                'photos.*'        => 'required_with:photos|image|mimes:jpeg,jpg,png,gif|max:500',
                'photos'          => 'Array',
                'cover'           => 'required|image|mimes:jpeg,jpg,png,gif|max:500'
            ]);
        }

        return $rules;
    }
}
