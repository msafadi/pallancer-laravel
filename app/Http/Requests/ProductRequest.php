<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //$user = Auth::user();
        //return $user->hasPermission('products.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|min:3',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'image',
            'gallery.*' => 'image',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute is required!',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('Product Name'),
        ];
    }
}
