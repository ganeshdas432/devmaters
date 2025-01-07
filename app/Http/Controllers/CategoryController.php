<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;

class CategoryController extends Controller
{

    public function category_list()
    {
        return Inertia::render('category/CategoryList');
    }
    public function category_api()
    {
        $response = Category::all(); // Or any other query you need
        return response()->json(['categories' => $response]);
    }

    public function create()
    {
        return Inertia::render('category/AddCategory');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'shop_type' => 'required|string',
                'image' => 'nullable|image|max:1024', // max 1MB
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('category', 'public');
            }

            Category::create([
                'title' => $request->title,
                'shop_type' => $request->shop_type,
                'image' => $imagePath,
            ]);

            return response()->json(['success' => 'Category created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category.'], 500);
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return Inertia::render('category/EditCategory', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'shop_type' => 'required|string',
                'image' => 'nullable|image|max:1024', // max 1MB
            ]);

            $category = Category::findOrFail($id);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('category', 'public');
            } else {
                $imagePath = $category->image;
            }

            $category->update([
                'title' => $request->title,
                'shop_type' => $request->shop_type,
                'image' => $imagePath,
            ]);

            return response()->json(['success' => 'Category updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category.'], 500);
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully!'], 200);
    }
}