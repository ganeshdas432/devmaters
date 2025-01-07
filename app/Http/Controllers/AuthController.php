<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\User; // Import your User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import the Validator class
use Illuminate\Support\Facades\Auth; // Import the Auth class if needed
use Illuminate\Support\Facades\Hash; // Import the Hash class

use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'mobile' => 'required|string',
            'age' => 'required|numeric',
            'gender' => 'required|string|in:male,female,other',
            'status' => 'required',
            'role' => 'required|string|in:vendor,customer,rider',
            'zone_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'age' => $request->age,
            'gender' => $request->gender,
            'status' => $request->status,
            'role' => $request->role,
            'zone_id' => $request->zone_id
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Revoke the token that was used to authenticate the current request
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    public function getRiders()
    {
        $riders = User::where('role', 'rider')->get();
        return response()->json(['riders' => $riders]);
    }




    public function user_by_usertype($usertype)
    {
        $response = User::where('role', $usertype)->with('status')->get(); // Or any other query you need
        return response()->json(['users' => $response]);
    }


    public function edit($id)
    {
        $response = User::findOrFail($id);
        return Inertia::render('appuser/Edit', ['user' => $response]);
    }

    public function update(Request $request, $id)
    {
        Log::info('Update request received', ['id' => $id, 'data' => $request->all()]);

        $validated = $request->validate([
            'user_status' => 'required|integer|in:1,2', // Validate allowed values
        ]);

        $user = User::findOrFail($id);

        $user->update(['status' => $validated['user_status']]);

        Log::info('User updated successfully', ['user' => $user]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function view($id)
    {
        $response = User::with('kyc')->findOrFail($id);
        return response()->json(['user' => $response]);
    }

    public function activateAccount($id)
    {
        $user = User::findOrFail($id);
        $user->status = 1; // Set status to active
        $user->save();

        return response()->json(['message' => 'Account activated successfully']);
    }
}