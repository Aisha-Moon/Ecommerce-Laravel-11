<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductSliderRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this based on your authorization logic
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'short_des' => 'sometimes|nullable|string|max:500',
            'price' => 'sometimes|required|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_id' => 'sometimes|required|exists:products,id',
        ];
    }
}
