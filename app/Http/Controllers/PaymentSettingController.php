<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $settings = PaymentSetting::first() ?? PaymentSetting::create([
            'cash_payment_enabled' => true,
            'digital_payment_enabled' => false
        ]);

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cash_payment_enabled' => 'required|boolean',
            'digital_payment_enabled' => 'required|boolean',
            'razorpay_key' => 'required_if:digital_payment_enabled,true',
            'razorpay_secret' => 'required_if:digital_payment_enabled,true'
        ]);

        $settings = PaymentSetting::first() ?? new PaymentSetting();
        $settings->fill($validated);
        $settings->save();

        return response()->json([
            'message' => 'Payment settings updated successfully',
            'settings' => $settings
        ]);
    }
}
