<?php

namespace App\Http\Controllers;

use App\Models\FcmConfig;
use Illuminate\Http\Request;

class FcmConfigController extends Controller
{
    public function index()
    {
        $config = FcmConfig::first();
        return response()->json($config ?? new FcmConfig());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firebase_enabled' => 'required|boolean',
            'api_key' => 'required_if:firebase_enabled,true|string|nullable',
            'project_id' => 'required_if:firebase_enabled,true|string|nullable',
            'auth_domain' => 'required_if:firebase_enabled,true|string|nullable',
            'storage_bucket' => 'required_if:firebase_enabled,true|string|nullable',
            'messaging_sender_id' => 'required_if:firebase_enabled,true|string|nullable',
            'app_id' => 'required_if:firebase_enabled,true|string|nullable',
            'measurement_id' => 'nullable|string',
            'server_key' => 'required_if:firebase_enabled,true|string|nullable',
        ]);

        $config = FcmConfig::first();

        if ($config) {
            $config->update($validated);
        } else {
            $config = FcmConfig::create($validated);
        }

        return response()->json([
            'message' => 'Firebase configuration saved successfully',
            'data' => $config
        ]);
    }
}
