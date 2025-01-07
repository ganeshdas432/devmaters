<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\AssignVehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        return response()->json($vehicles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_area' => 'required|string|max:255',
        ]);

        $vehicle = Vehicle::create($request->all());
        return response()->json($vehicle, 201);
    }

    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json($vehicle);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'delivery_area' => 'required|string|max:255',
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($request->all());
        return response()->json($vehicle);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(null, 204);
    }

    public function assignRiderToVehicle(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'rider_id' => 'required|exists:users,id',
            'vehicle_number' => 'required|string|max:255',
        ]);

        $assignVehicle = AssignVehicle::create([
            'vehicle_id' => $request->vehicle_id,
            'rider_id' => $request->rider_id,
            'vehicle_number' => $request->vehicle_number,
        ]);

        return response()->json($assignVehicle, 201);
    }
}