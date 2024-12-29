<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ZoneController extends Controller
{
    public function index()
    {
        return response()->json([
            'zones' => Zone::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_charge_per_km' => 'required|numeric|min:0',
            'base_delivery_charge' => 'required|numeric|min:0',
            'minimum_delivery_charge' => 'required|numeric|min:0',
            'coordinates' => 'required|array',
            'description' => 'nullable|string'
        ]);

        $zone = Zone::create($validated);

        return response()->json([
            'message' => 'Zone created successfully',
            'zone' => $zone
        ], 201);
    }

    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_charge_per_km' => 'required|numeric|min:0',
            'base_delivery_charge' => 'required|numeric|min:0',
            'minimum_delivery_charge' => 'required|numeric|min:0',
            'coordinates' => 'required|array',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $zone->update($validated);

        return response()->json([
            'message' => 'Zone updated successfully',
            'zone' => $zone
        ]);
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();
        return response()->json(['message' => 'Zone deleted successfully']);
    }
}
