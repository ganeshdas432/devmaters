<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{

    public function index()
    {
        // Eager load relationships and fetch products
        $products = Product::with(['category', 'ratings', 'attributes', 'shop'])->get();

        // Add average rating to each product
        foreach ($products as $product) {
            // Ensure averageRating method is available and returns a valid number
            $product->average_rating = $product->averageRating();
        }

        // Return products in JSON format with HTTP 200 status
        return response()->json($products, 200);
    }


    public function add_product()
    {
        return Inertia::render('product/AddProduct');
    }

    public function store(Request $request)
    {


        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'cat_id' => 'required|exists:categories,id',
            'product_name' => 'required|string',
            'description' => 'nullable|string',
            'time' => 'required|integer',
            'type' => 'required|string',
            'image' => 'nullable|image|max:1024',
            'attributes' => 'required|array|min:1',
            'attributes.*.title' => 'required|string',
            'attributes.*.value' => 'required|string',
            'attributes.*.price' => 'required|numeric',
            'attributes.*.mrp' => 'required|numeric',
            'attributes.*.unit' => 'required|string',
        ]);

        // Handle the product image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create the product
        $product = Product::create([
            'shop_id' => $request->shop_id,
            'cat_id' => $request->cat_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'time' => $request->time,
            'type' => $request->type,
            'image' => $imagePath,
            'status' => 'active'
        ]);

        // Create the attributes
        foreach ($request->input('attributes') as $attribute) {
            Attribute::create([
                'product_id' => $product->id,
                'title' => $attribute['title'],
                'value' => $attribute['value'],
                'price' => $attribute['price'],
                'mrp' => $attribute['mrp'],
                'unit' => $attribute['unit'],
            ]);
        }

        return redirect()->route('add_product')->with('success', 'Product created successfully.');
    }


    public function show($id)
    {
        $product = Product::with('attributes')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Validation logic
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required|exists:shops,id',
            'cat_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time' => 'required|integer',
            'type' => 'required|integer',
            'attributes' => 'required|array',
            'attributes.*.title' => 'required|string|max:255',
            'attributes.*.value' => 'required|string|max:255',
            'attributes.*.price' => 'required|numeric',
            'attributes.*.mrp' => 'required|numeric',
            'attributes.*.unit' => 'required|exists:units,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update product
        $product->shop_id = $request->shop_id;
        $product->cat_id = $request->cat_id;
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->time = $request->time;
        $product->type = $request->type;

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($product->image) {
                Storage::delete($product->image);
            }

            // Store the new image and save the path
            $imagePath = $request->file('image')->store('product_images');
            $product->image = $imagePath;
        }

        $product->save();

        // Update product attributes
        $product->attributes()->delete(); // Clear old attributes
        foreach ($request->input('attributes') as $attribute) {
            $product->attributes()->create([
                'title' => $attribute['title'],
                'value' => $attribute['value'],
                'price' => $attribute['price'],
                'mrp' => $attribute['mrp'],
                'unit' => $attribute['unit'],
            ]);
        }

        return response()->json(['message' => 'Product updated successfully'], 200);
    }

    public function edit($id)
    {
        // Fetch the product with attributes
        $product = Product::with('attributes')->findOrFail($id);

        return Inertia::render('product/Edit', [
            'product' => $product, // Pass the product data to the edit page
            'productId' => $product->id // Ensure productId is passed as a prop
        ]);
    }
}
