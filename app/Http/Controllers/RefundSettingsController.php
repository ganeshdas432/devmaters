<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RefundSetting;

class RefundSettingsController extends Controller
{
    /**
     * Update the refund settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'max_refund_days' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $refundSetting = RefundSetting::first();
        if (!$refundSetting) {
            $refundSetting = new RefundSetting();
        }

        $refundSetting->max_refund_days = $request->input('max_refund_days');
        $refundSetting->is_active = $request->input('is_active');
        $refundSetting->save();

        return response()->json(['message' => 'Refund settings updated successfully']);
    }
}
