<?php

namespace App\Http\Controllers;

use App\Models\CancellationReason;
use App\Models\Role;
use Illuminate\Http\Request;

class CancellationReasonController extends Controller
{
    public function index()
    {
        return CancellationReason::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'type' => 'required|string|exists:roles,title',
        ]);

        $cancellationReason = new CancellationReason($request->all());
        $cancellationReason->save();

        return $cancellationReason;
    }

    public function update(Request $request, CancellationReason $cancellationReason)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'type' => 'required|string|exists:roles,title',
        ]);

        $cancellationReason->fill($request->all());
        $cancellationReason->save();

        return $cancellationReason;
    }

    public function destroy(CancellationReason $cancellationReason)
    {
        $cancellationReason->delete();

        return response()->noContent();
    }
}