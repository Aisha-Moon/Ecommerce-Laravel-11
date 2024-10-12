<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductDetailsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set this to true if you want to authorize all users
    }

    public function rules()
    {
        return [
            'img1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'img4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'des' => 'required|string',
            'color' => 'required|string|max:200',
            'size' => 'required|string|max:200',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
