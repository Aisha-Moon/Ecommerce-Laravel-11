<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helper\ResponseHelper; 

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return ResponseHelper::Out('Brands retrieved successfully.', $brands, 200); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $filename = $img->getClientOriginalName();
            $imagePath = "uploads/brands/$filename";
            $img->move(public_path('uploads/brands'), $filename);
        }

        $brand = Brand::create([
            'name' => $request->input('name'),
            'image' => $imagePath,
        ]);

        return ResponseHelper::Out('Brand created successfully.', $brand, 201); 
    }

    public function show($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            return ResponseHelper::Out('Brand retrieved successfully.', $brand, 200); 
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Brand not found.', null, 404); 
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = Brand::findOrFail($id);
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $filename = $img->getClientOriginalName();
            $img_url = "uploads/brands/$filename";
            $img->move(public_path('uploads/brands'), $filename);

            if ($brand->image) {
                $imagePath = public_path($brand->image);
                if (file_exists($imagePath)) {
                    try {
                        unlink($imagePath);
                    } catch (Exception $e) {
                        return ResponseHelper::Out('Error deleting old image: ' . $e->getMessage(), null, 500); 
                    }
                }
            }

            $brand->image = $img_url;
        }
        $brand->name = $request->input('name');
        $brand->save();

        return ResponseHelper::Out('Brand updated successfully.', $brand, 200); 
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return ResponseHelper::Out('Brand deleted successfully.', null, 200); 
    }
}
