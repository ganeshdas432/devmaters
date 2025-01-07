<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\CancellationReason;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return Role::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        return Role::create($request->all());
    }

    public function show(Role $role)
    {
        return $role;
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $role->update($request->all());

        return $role;
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->noContent();
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function storeCancellationReason(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        return CancellationReason::create($request->all());
    }

    public function updateCancellationReason(Request $request, CancellationReason $cancellationReason)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $cancellationReason->update($request->all());

        return $cancellationReason;
    }

    public function deleteCancellationReason(CancellationReason $cancellationReason)
    {
        $cancellationReason->delete();

        return response()->noContent();
    }

    public function getCancellationReasons()
    {
        return CancellationReason::all();
    }
}