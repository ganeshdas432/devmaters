<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Appuser;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;




class AppUserController extends Controller
{

    public function profile($id)
    {
        return Inertia::render('appuser/UserProfile', ['userId' => $id]);
    }

    public function add_rider()
    {
        return Inertia::render('appuser/AddRider');
    }

    public function Rider_list()
    {
        return Inertia::render('appuser/RiderList');
    }


    public function vendor_list()
    {
        return Inertia::render('appuser/VendorList');
    }


    public function customer_list()
    {
        return Inertia::render('appuser/CustomerList');
    }




    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'user_status' => 'required|in:1,2'
            ]);

            // Find the user by ID
            $user = User::findOrFail($id);

            // Update status
            $user->status = $request->user_status;
            $user->save();

            // Return success response with updated status info
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => [
                    'id' => $user->status,
                    'title' => $user->status == 1 ? 'Active' : 'Deactive'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getCounts()
    {
        // Get total counts by user_type
        $customerCount = User::where('role', 'customer')->count();
        $vendorCount = User::where('role', 'vendor')->count();
        $riderCount = user::where('role', 'rider')->count();
        $orderCount = Order::count();
        $productCount = Product::count();

        // Return the counts as a JSON response
        return response()->json([
            'customer_count' => $customerCount,
            'vendor_count' => $vendorCount,
            'rider_count' => $riderCount,
            'product_count' => $productCount,
            'order_count' => $orderCount,
        ]);
    }
}