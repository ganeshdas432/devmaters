<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\RideOrder;
use App\Models\PorterOrder;
use App\Models\Orderdetail;
use App\Models\RideOrderdetail;
use App\Models\PorterOrderdetail;
use App\Models\OrderStatus;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB; // Import DB facade



class OrderController extends Controller
{
    public function list()
    {
        return Inertia::render('order/OrderList');
    }
    public function rideOrderList()
    {
        return Inertia::render('order/OrderList');
    }
    public function porterOrderList()
    {
        return Inertia::render('order/OrderList');
    }

    public function order_api()
    {
        $response = Order::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->get();
        return response()->json(['orders' => $response]);
    }

    public function ride_order_api()
    {
        $response = RideOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->get();
        return response()->json(['ride_orders' => $response]);
    }

    public function porter_order_api()
    {
        $response = PorterOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->get();
        return response()->json(['porter_orders' => $response]);
    }



    public function order_bycid_api($cid)
    {
        $response = Order::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->where('cid', $cid)->get();
        return response()->json(['orders' => $response]);
    }

    public function rider_order_bycid_api($cid)
    {
        $response = RideOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->where('cid', $cid)->get();
        return response()->json(['orders' => $response]);
    }

    public function porter_order_bycid_api($cid)
    {
        $response = PorterOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])->where('cid', $cid)->get();
        return response()->json(['orders' => $response]);
    }




    public function order_details_api($id)
    {
        $order = Order::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])
            ->findOrFail($id);


        return response()->json([
            'order' => $order
        ]);
    }

    public function ride_order_details_api($id)
    {
        $order = RideOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])
            ->findOrFail($id);


        return response()->json([
            'order' => $order
        ]);
    }
    public function porter_order_details_api($id)
    {
        $order = PorterOrder::with(['customer', 'orderStatus', 'orderDetails', 'delivery'])
            ->findOrFail($id);


        return response()->json([
            'order' => $order
        ]);
    }




    public function details($id)
    {
        return Inertia::render('order/OrderDetailView', [
            'orderId' => $id,
        ]);
    }


    // Update order status
    public function updateStatus(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'status' => 'required|exists:order_statuses,id'
        ]);

        $order = Order::findOrFail($orderId);
        $order->update(['order_status' => $validatedData['status']]);

        return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
    }

    public function assignDeliveryBoy(Request $request, $orderId)
    {

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $delivery = Delivery::find($order->did);
        // Assign the delivery boy to the order
        $delivery->rider_id = $request->input('rider_id');
        $delivery->save();

        return response()->json(['message' => 'Delivery boy assigned successfully', 'order' => $order], 200);
    }

    public function getStatus()
    {

        $statuses = OrderStatus::all();
        return response()->json(['statuses' => $statuses]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'cid' => 'required',
            'subtotal' => 'required',
            'order_status' => 'required',
            'delivery' => 'required|array',
            'delivery.pickup_latitude' => 'required',
            'delivery.pickup_longitude' => 'required',
            'delivery.drop_latitude' => 'required',
            'delivery.drop_longitude' => 'required',
            'delivery.pickup_address' => 'required|string',
            'delivery.drop_address' => 'required|string',
            'delivery.delivery_type' => 'required|string',
            'delivery.delivery_details' => 'required|string',
            'delivery.pickup_contact' => 'required|string',
            'delivery.drop_contact' => 'required|string',
            'delivery.drop_to' => 'required|string',
            'delivery.order_value' => 'required|string',
            'delivery.order_weight' => 'required|string',
            'delivery.order_payment_status' => 'required|string',
            'delivery.order_payment_type' => 'required|string',
            'delivery.distance' => 'required|string',
            'delivery.delivery_cost' => 'required|string',
            'delivery.tips' => 'required|string',
            'delivery.rider_earning' => 'required|string',
            'delivery.rider_id' => 'required|string',
            'delivery.customer_id' => 'required|string',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|integer',
            'order_details.*.product_name' => 'required|string',
            'order_details.*.product_price' => 'required|numeric',
            'order_details.*.selected_attribute' => 'required|string',
            'order_details.*.quantity' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            // Save delivery data
            $delivery = Delivery::create($validatedData['delivery']);

            // Save order data with delivery ID
            $order = Order::create([
                'cid' => $validatedData['cid'],
                'did' => $delivery->id,
                'subtotal' => $validatedData['subtotal'],
                'order_status' => $validatedData['order_status'],
            ]);

            // Save each order detail
            foreach ($validatedData['order_details'] as $detail) {
                $detail['order_id'] = $order->id;
                Orderdetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Order creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function storeRider(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'cid' => 'required',
            'subtotal' => 'required',
            'order_status' => 'required',
            'delivery' => 'required|array',
            'delivery.pickup_latitude' => 'required',
            'delivery.pickup_longitude' => 'required',
            'delivery.drop_latitude' => 'required',
            'delivery.drop_longitude' => 'required',
            'delivery.pickup_address' => 'required|string',
            'delivery.drop_address' => 'required|string',
            'delivery.delivery_type' => 'required|string',
            'delivery.delivery_details' => 'required|string',
            'delivery.pickup_contact' => 'required|string',
            'delivery.drop_contact' => 'required|string',
            'delivery.drop_to' => 'required|string',
            'delivery.order_value' => 'required|string',
            'delivery.order_weight' => 'required|string',
            'delivery.order_payment_status' => 'required|string',
            'delivery.order_payment_type' => 'required|string',
            'delivery.distance' => 'required|string',
            'delivery.delivery_cost' => 'required|string',
            'delivery.tips' => 'required|string',
            'delivery.rider_earning' => 'required|string',
            'delivery.rider_id' => 'required|string',
            'delivery.customer_id' => 'required|string',
            'order_details' => 'required|array',
            'order_details.*.person_count' => 'required|integer',
            'order_details.*.vehicle_type' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            // Save delivery data
            $delivery = Delivery::create($validatedData['delivery']);

            // Save order data with delivery ID
            $order = RideOrder::create([
                'cid' => $validatedData['cid'],
                'did' => $delivery->id,
                'subtotal' => $validatedData['subtotal'],
                'order_status' => $validatedData['order_status'],
            ]);

            // Save each order detail
            foreach ($validatedData['order_details'] as $detail) {
                $detail['order_id'] = $order->id;
                RideOrderdetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'message' => 'Ride Order created successfully',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Ride Order creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storePorter(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'cid' => 'required',
            'subtotal' => 'required',
            'order_status' => 'required',
            'delivery' => 'required|array',
            'delivery.pickup_latitude' => 'required',
            'delivery.pickup_longitude' => 'required',
            'delivery.drop_latitude' => 'required',
            'delivery.drop_longitude' => 'required',
            'delivery.pickup_address' => 'required|string',
            'delivery.drop_address' => 'required|string',
            'delivery.delivery_type' => 'required|string',
            'delivery.delivery_details' => 'required|string',
            'delivery.pickup_contact' => 'required|string',
            'delivery.drop_contact' => 'required|string',
            'delivery.drop_to' => 'required|string',
            'delivery.order_value' => 'required|string',
            'delivery.order_weight' => 'required|string',
            'delivery.order_payment_status' => 'required|string',
            'delivery.order_payment_type' => 'required|string',
            'delivery.distance' => 'required|string',
            'delivery.delivery_cost' => 'required|string',
            'delivery.tips' => 'required|string',
            'delivery.rider_earning' => 'required|string',
            'delivery.rider_id' => 'required|string',
            'delivery.customer_id' => 'required|string',
            'order_details' => 'required|array',
            'order_details.*.weight' => 'required',
            'order_details.*.item_type' => 'required|integer',
        ]);



        DB::beginTransaction();

        try {
            // Save delivery data
            $delivery = Delivery::create($validatedData['delivery']);

            // Save order data with delivery ID
            $order = PorterOrder::create([
                'cid' => $validatedData['cid'],
                'did' => $delivery->id,
                'subtotal' => $validatedData['subtotal'],
                'order_status' => $validatedData['order_status'],
            ]);

            // Save each order detail
            foreach ($validatedData['order_details'] as $detail) {
                $detail['order_id'] = $order->id;
                PorterOrderdetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'message' => 'Porter Order created successfully',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Porter Order creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
