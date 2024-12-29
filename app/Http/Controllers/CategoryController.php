<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Category;

class CategoryController extends Controller
{
    public function category_api()
    {
        $response = category::all(); // Or any other query you need
        return response()->json(['categories' => $response]);
    }


    public function create()
    {
        return Inertia::render('category/AddCategory');
    }

    public function store(Request $request)
    {
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
    
        return redirect()->route('category.add')->with('success', 'Category created successfully.');
    }

    public function edit($id)
{
    $category = Category::findOrFail($id);
    return Inertia::render('category/EditCategory', ['category' => $category]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'shop_type' => 'required|string',
        'image' => 'nullable|image|max:1024', // max 1MB
        
        
    ]);
    // Handle image upload and shop update logic
    $category = Category::findOrFail($id);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('category', 'public');
    } else {
        $imagePath = $category->image;
    }

    // Update the shop with individual fields
    $shop->update([
        'shop_type' => $request->shop_type,
        'image' => $imagePath,
        'title' => $request->title,
       
    ]);

    return redirect()->route('category.add')->with('success', 'Category updated successfully.');
}




    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully!'], 200);
    }

}
