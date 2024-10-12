<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'short_desc' => 'nullable|string|max:500',
            'price' => 'required|numeric',
            'discount' => 'nullable|boolean',
            'discount_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|boolean',
            'star' => 'nullable|numeric|min:0|max:5',
            'remark' => 'nullable|in:popular,new,top,special,trending,regular',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}
