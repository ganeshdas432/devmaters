<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Unit;

class UnitController extends Controller
{

    public function unit_list()
    {
        return Inertia::render('unit/List');
    }
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            Unit::create([
                'title' => $validated['title'],
            ]);

            return response()->json(['message' => 'Unit created successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create unit.'], 500);
        }
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
        ]);

        $response = Unit::findOrFail($id);

        try {
            $response->update([
                'title' => $validated['title'],
            ]);

            return response()->json(['message' => 'Unit updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update unit.'], 500);
        }
    }
}
