<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Slider;
use App\Models\Details;
use App\Models\Charge;
use App\Models\Page;


class SettingController extends Controller
{
    function index()
    {

        return Inertia::render('settings/index');
    }

    function list()
    {


        return Inertia::render('settings/sliders');
    }

    function company_details()
    {

        return Inertia::render('settings/company_details');
    }

    public function chargelist()
    {
        return Inertia::render('settings/charges');
    }


    public function sliderallapi()
    {
        $response = Slider::all(); // Or any other query you need
        return response()->json(['sliders' => $response]);
    }

    public function chargeallapi()
    {
        $response = Charge::all(); // Or any other query you need
        return response()->json(['charges' => $response]);
    }

    public function pageallapi()
    {
        $response = Page::all();
        return response()->json(['pages' => $response]);
    }


    function sliderstore(Request $request)
    {
        $request->validate([
            'shop_type' => 'required|string',
            'image' => 'nullable|image|max:1024', // max 1MB

        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sliders', 'public');
        }

        Slider::create([
            'url' => $imagePath,
            'shop_type' => $request->shop_type,
        ]);

        return redirect()->route('settings.sliders')->with('success', 'Image Added successfully.');
    }

    public function chargestore(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|string',
            'fs_per_km' => 'required|numeric',
            'gs_per_km' => 'required|numeric',
            'p_per_km' => 'required|numeric',
            'r_per_km' => 'required|numeric',
            'free_delivery_on' => 'required|numeric',
            'r_per_person' => 'required|numeric',
            'p_per_weight' => 'required|numeric',
            'p_weight' => 'required|numeric',
            'tax_per' => 'required|numeric',
        ]);

        Charge::create($request->all());
        return redirect()->route('settings.charges')->with('success', 'charges added successfull');
    }


    public function slidedelete($id)
    {
        $item = Slider::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully!'], 200);
    }

    public function chargeshow($id)
    {
        $charge = Charge::findOrFail($id);
        return inertia('Charges/Show', ['charge' => $charge]);
    }

    public function chargeedit($id)
    {
        $charge = Charge::findOrFail($id);
        return inertia('settings/Edit', ['charge' => $charge]);
    }

    public function chargeupdate(Request $request, $id)
    {
        $request->validate([
            'zone_id' => 'required|string',
            'fs_per_km' => 'required|numeric',
            'gs_per_km' => 'required|numeric',
            'p_per_km' => 'required|numeric',
            'r_per_km' => 'required|numeric',
            'free_delivery_on' => 'required|numeric',
            'r_per_person' => 'required|numeric',
            'p_per_weight' => 'required|numeric',
            'p_weight' => 'required|numeric',
            'tax_per' => 'required|numeric',
        ]);

        $charge = Charge::findOrFail($id);
        $charge->update($request->all());

        return redirect()->route('settings.charges')->with('success', 'Charge updated successfully.');
    }

    public function chargedestroy($id)
    {
        $charge = Charge::findOrFail($id);
        $charge->delete();

        return redirect()->route('settings.charges')->with('success', 'Charge deleted successfully.');
    }


    public function show()
    {
        // Fetch the existing company details
        $response = Details::first(); // Adjust based on your requirements
        return response()->json($response);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
        ]);

        // Find or create the company details
        $company = Details::firstOrNew();

        $company->company_name = $request->title;
        $company->company_mobile = $request->mobile;
        $company->company_email = $request->email;
        $company->company_address = $request->address;

        // Handle file uploads
        if ($request->hasFile('company_logo_square')) {
            $imagePath = $request->file('company_logo_square')->store('details', 'public');
            $company->company_logo_square = $imagePath;
        }

        if ($request->hasFile('company_logo_rectangular')) {
            $imagePath = $request->file('company_logo_rectangular')->store('details', 'public');
            $company->company_logo_rectangular = $imagePath;
        }

        $company->save();

        return redirect()->back()->with('success', 'Company details updated successfully');
    }
}
