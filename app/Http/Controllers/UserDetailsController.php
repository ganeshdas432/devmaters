<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDetails;

class UserDetailsController extends Controller
{
    
    public function index()
    {
        $users = UserDetails::all();
        return response()->json($users);
    }

    /**
     * Fetch a single user by ID.
     */
    public function show($id)
    {
        $user = UserDetails::where('cid', $id)->firstOrFail();
         return response()->json($user);
    }

    /**
     * Store a newly created user.
     */
public function store(Request $request)
{
    // Define validation rules
    $rules = [
        'name' => 'required|string',
        'mobile_no' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'id_proof' => 'required|string',
        'id_no' => 'required|string',
        'age' => 'required|integer',
        'gender' => 'required|string',
        'status' => 'required',
    ];

    // Validate request
    try {
        $validated = $request->validate($rules);
    } catch (ValidationException $e) {
        // Return a JSON response with validation errors
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }

    // Create the user record in UserDetails table
    $user = UserDetails::create($validated);

    // Return success message and user details
    return response()->json([
        'message' => 'User created successfully!',
        'user_details' => $user
    ], 201);
}

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = UserDetails::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'mobile_no' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'id_proof' => 'sometimes|string',
            'id_no' => 'sometimes|string',
            'age' => 'sometimes|integer',
            'gender' => 'sometimes|string',
            'status' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully!', 'user' => $user]);
    }

    /**
     * Delete the specified user.
     */
    public function destroy($id)
    {
        $user = UserDetails::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!']);
    }
}
