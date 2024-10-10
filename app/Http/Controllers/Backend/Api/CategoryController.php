<?php

namespace App\Http\Controllers\Backend\Api;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper; 
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return ResponseHelper::Out('category retrieved successfully.', $category, 200); 
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
            $imagePath = "uploads/category/$filename";
            $img->move(public_path('uploads/category'), $filename);
        }

        $category = Category::create([
            'name' => $request->input('name'),
            'image' => $imagePath,
        ]);

        return ResponseHelper::Out('category created successfully.', $category, 201); 
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return ResponseHelper::Out('Category retrieved successfully.', $category, 200); 
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::Out('Category not found.', null, 404); 
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $filename = $img->getClientOriginalName();
            $img_url = "uploads/category/$filename";
            $img->move(public_path('uploads/category'), $filename);

            if ($category->image) {
                $imagePath = public_path($category->image);
                if (file_exists($imagePath)) {
                    try {
                        unlink($imagePath);
                    } catch (Exception $e) {
                        return ResponseHelper::Out('Error deleting old image: ' . $e->getMessage(), null, 500); 
                    }
                }
            }

            $category->image = $img_url;
        }
        $category->name = $request->input('name');
        $category->save();

        return ResponseHelper::Out('Category updated successfully.', $category, 200); 
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return ResponseHelper::Out('Category deleted successfully.', null, 200); 
    }
}
