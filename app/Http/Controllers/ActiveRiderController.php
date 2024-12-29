<?php

namespace App\Http\Controllers;
use App\Models\ActiveRider;
use Illuminate\Http\Request;

class ActiveRiderController extends Controller
{

    public function getStatus($rider_id){
        $response = ActiveRider::where('rider_id', $rider_id)->firstOrFail();

        return response()->json($response);
    }
    public function updateStatus(Request $request, $rider_id){
        $request->validate([
            'food' => 'boolean',
            'grocery' => 'boolean',
            'ride' => 'boolean',
            'porter' => 'boolean',
        ]);

        $rider = ActiveRider::firstOrNew(['rider_id' => $rider_id]);


        $rider->food = $request->input('food', $rider->food);
        $rider->grocery = $request->input('grocery', $rider->grocery);
        $rider->ride = $request->input('ride', $rider->ride);
        $rider->porter = $request->input('porter', $rider->porter);

    // Save the rider record (whether it was new or updated)
    $rider->save();

    return response()->json(['message' => 'Rider status updated successfully', 'rider' => $rider]);

    }
}
