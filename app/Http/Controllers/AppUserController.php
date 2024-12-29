<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Appuser;

use App\Models\User;




class AppUserController extends Controller
{



    public function store(Request $request)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|string',
            'dob' => 'nullable|string',
            'user_id' => 'nullable|string',
            'user_type' => 'required|string',
            'user_status' => 'required|string', // max 1MB

        ]);



        $user = Appuser::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'user_id' => $request->user_id,
            'user_type' => $request->user_type,
            'user_status' => $request->user_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
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
}
