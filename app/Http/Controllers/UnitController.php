<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Unit;

class UnitController extends Controller
{
    public function unit_api()
    {
        $response = Unit::all(); // Or any other query you need
        return response()->json(['units' => $response]);
    }


    public function create()
    {
        return Inertia::render('unit/Add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string',
            
            
        ]);
    
       
        Unit::create([
            'title' => $request->title,
            'value' => $request->value,
            
        ]);
    
        return redirect()->route('unit.add')->with('success', 'Unit created successfully.');
    }

    public function edit($id)
{
    $response = Unit::findOrFail($id);
    return Inertia::render('unit/Edit', ['unit' => $response]);
}


    public function destroy($id)
    {
        $item = Unit::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully!'], 200);
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'value' => 'required|string|max:255',
    ]);

    // Handle image upload and shop update logic
    $response = Unit::findOrFail($id);

    $response->update([
        'title' => $request->title,
        'value' =>  $request->value,
        
    ]);

    return redirect()->route('unitlist')->with('success', 'Unit updated successfully.');
}

}
