<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
        public function index(Request $request)
        {
            // Check if 'cid' is provided in the query string
            $cid = $request->query('cid');

            // If 'cid' is provided, filter locations by 'cid', otherwise return all locations
            if ($cid) {
                $locations = Address::where('cid', $cid)->get();
            } else {
                $locations = Address::all();
            }

            // Return the filtered or full list of locations as JSON
            return response()->json($locations);
        }



     public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'cid' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'address' => 'required|string|max:255',
        ]);

        // Create a new location
        $location = Address::create($request->all());

        // Return the created location
        return response()->json($location, 201);
    }

    // Update an existing location
    public function update(Request $request, $id)
    {
        // Find the location
        $location = Address::findOrFail($id);

        // Validate the request
        $request->validate([
            'cid' => 'sometimes|string|max:255',
            'lat' => 'sometimes|numeric',
            'long' => 'sometimes|numeric',
            'address' => 'sometimes|string|max:255',
        ]);

        // Update the location
        $location->update($request->all());

        // Return the updated location
        return response()->json($location);
    }

    // Delete a location
    public function destroy($id)
    {
        // Find the location
        $location = Address::findOrFail($id);

        // Delete the location
        $location->delete();

        // Return a success message
        return response()->json(['message' => 'Location deleted successfully']);
    }
}
