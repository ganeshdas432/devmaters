<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    // Method to fetch all deliveries
        public function index()
        {
            // Use `get()` instead of `all()` to retrieve all deliveries with eager loading
            $response = Delivery::with(['rider', 'customer'])->get();
            return response()->json(['deliveries' => $response]);
        }

    public function deliveries_by_cid($cid){
         $response = Delivery::where('customer_id',$cid)->get();
        return response()->json(['deliveries' => $response]);
    }

    // Inertia view for the delivery list
    public function list()
    {
        return Inertia::render('delivery/DeliveryList');
    }

    // Method to handle the submission of new delivery data
    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'drop_latitude' => 'required',
            'drop_longitude' => 'required',
            'pickup_address'=>'required',
            'drop_address'=>'required',
            'delivery_type' => 'required',
            'delivery_details' => 'nullable',
            'pickup_contact' => 'required',
            'drop_contact' => 'required',
            'drop_to' => 'required',
            'order_value' => 'required',
            'order_weight' => 'required',
            'order_payment_status' => 'required',
            'order_payment_type' => 'required',
            'distance' => 'required',
            'delivery_cost' => 'required',
            'tips' => 'nullable',
            'rider_earning' => 'nullable',
            'rider_id' => 'nullable',
            'customer_id' => 'nullable',
        ]);

        // Create a new delivery record
        $delivery = Delivery::create($validated);

        // Return a success response
        return response()->json([
            'message' => 'Delivery created successfully!',
            'delivery' => $delivery
        ], 201);
    }


   public function delivery_details_api($id)
        {
                $delivery = Delivery::with(['rider', 'customer'])
                            ->findOrFail($id);


                return response()->json([
                    'delivery' => $delivery
                ]);
     }



     public function details($id)
         {

            return Inertia::render('delivery/DeliveryDetails', [
                'deliveryId' => $id,
            ]);
         }

    
    public function pending_orders($delivery_type){
        $response=Delivery::where('delivery_type',$delivery_type)->where('rider_id',1)->get();
        return response()->json($response);

    }
    
    public function getRideOrders(Request $request)
    {
        $riderId = $request->query('rider_id', 1); // Default to rider_id=1 if not provided

        // Fetch deliveries with ride orders where order_status = 1
        $deliveries = Delivery::with(['rideOrders' => function ($query) {
            $query->where('order_status', 1)->with('customer');
        }])
        ->where('rider_id', $riderId)
        ->where('delivery_type','ride')
        ->get();
        return response()->json($deliveries);
    }

    public function order_accept($order_id, $rid)
    {
        // Find the Delivery record by ID
        $response = Delivery::findOrFail($order_id);
    
        // Update the rider_id field
        $response->rider_id = $rid;
    
        // Save the changes to the database
        $response->save();
    
        // Optionally return a response or confirmation
        return response()->json([
            'success' => true,
            'message' => 'Rider assigned successfully',
        ]);
    }
    

}

