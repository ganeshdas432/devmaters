<?php

namespace App\Http\Controllers;

use App\Models\EmailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailConfigController extends Controller
{
    public function index()
    {
        $config = EmailConfig::first();
        return response()->json($config ?? new EmailConfig());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'smtp_enabled' => 'required|boolean',
            'smtp_host' => 'required_if:smtp_enabled,true|string|nullable',
            'smtp_port' => 'required_if:smtp_enabled,true|integer|nullable',
            'smtp_username' => 'required_if:smtp_enabled,true|string|nullable',
            'smtp_password' => 'required_if:smtp_enabled,true|string|nullable',
            'from_name' => 'required_if:smtp_enabled,true|string|nullable',
            'from_email' => 'required_if:smtp_enabled,true|email|nullable',
            'encryption' => 'required_if:smtp_enabled,true|string|nullable|in:tls,ssl,none',
        ]);

        // Extract the value from encryption if it's an object
        if (isset($validated['encryption']) && is_array($validated['encryption']) && isset($validated['encryption']['value'])) {
            $validated['encryption'] = $validated['encryption']['value'];
        }

        // Handle the case where password is not provided but exists
        $config = EmailConfig::first();
        if ($config && empty($validated['smtp_password'])) {
            $validated['smtp_password'] = $config->smtp_password;
        }

        if ($config) {
            $config->update($validated);
        } else {
            $config = EmailConfig::create($validated);
        }

        return response()->json([
            'message' => 'Email configuration saved successfully',
            'data' => $config
        ]);
    }

    public function test(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $config = EmailConfig::first();

        if (!$config || !$config->smtp_enabled) {
            return response()->json([
                'message' => 'Email configuration is not enabled'
            ], 400);
        }

        // Configure mail settings for test
        config([
            'mail.mailers.smtp.host' => $config->smtp_host,
            'mail.mailers.smtp.port' => $config->smtp_port,
            'mail.mailers.smtp.encryption' => $config->encryption,
            'mail.mailers.smtp.username' => $config->smtp_username,
            'mail.mailers.smtp.password' => $config->smtp_password,
            'mail.from.address' => $config->from_email,
            'mail.from.name' => $config->from_name,
        ]);

        try {
            Mail::raw('This is a test email from your application to verify email configuration.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Test Email Configuration');
            });

            return response()->json([
                'message' => 'Test email sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
}
