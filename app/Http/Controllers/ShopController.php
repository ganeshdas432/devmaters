<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Shop;

class ShopController extends Controller
{

    public function shoplistapi()
{
    $shops = Shop::all(); // Or any other query you need
    return response()->json(['shops' => $shops]);
}

public function singleshopapi($id)
{
    // Fetch the shop by ID
    $shop = Shop::find($id);

    // Check if shop exists
    if (!$shop) {
        return response()->json(['error' => 'Shop not found'], 404);
    }

    // Return the shop data as JSON
    return response()->json($shop);
}


    public function create()
    {
        return Inertia::render('shop/AddShop');
    }

    public function store(Request $request)
{
    $request->validate([
        'shop_name' => 'required|string|max:255',
        'vendor_id' => 'required|string',
        'address' => 'required|string',
        'mobile' => 'required|string',
        'latitude' => 'nullable|string',
        'longitude' => 'nullable|string',
        'shop_type' => 'required|string',
        'image' => 'nullable|image|max:1024', // max 1MB
        
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('shops', 'public');
    }

    Shop::create([
        'shop_name' => $request->shop_name,
        'image' => $imagePath,
        'vendor_id' => $request->vendor_id,
        'address' => $request->address,
        'mobile' => $request->mobile,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'shop_type' => $request->shop_type,
        'status'=>'active',
    ]);

    return redirect()->route('shoplist')->with('success', 'Shop created successfully.');
}


public function edit($id)
{
    $shop = Shop::findOrFail($id);
    return Inertia::render('shop/EditShop', ['shop' => $shop]);
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'shop_name' => 'required|string|max:255',
        'image' => 'nullable|image|max:1024',
        'vendor_id' => 'required|integer',
        'address' => 'required|string',
        'mobile' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'shop_type' => 'required|string',
        'status' => 'required|string',

    ]);

    // Handle image upload and shop update logic
    $shop = Shop::findOrFail($id);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('shops', 'public');
    } else {
        $imagePath = $shop->image;
    }

    // Update the shop with individual fields
    $shop->update([
        'shop_name' => $request->shop_name,
        'image' => $imagePath,
        'vendor_id' => $request->vendor_id,
        'address' => $request->address,
        'mobile' => $request->mobile,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'shop_type' => $request->shop_type,
        'status' => $request->status,
    ]);

    return redirect()->route('vendorlist')->with('success', 'Shop updated successfully.');
}


public function updateStatus(Request $request, $id)
{
    $shop = Shop::findOrFail($id);
    $shop->status = $request->status;
    $shop->save();

    return response()->json(['success' => true, 'status' => $shop->status]);
}




}
