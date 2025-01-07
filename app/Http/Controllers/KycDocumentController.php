<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KycDocument;
use Inertia\Inertia;

class KycDocumentController extends Controller
{

    public function kyc_upload($userId, $kycType)
    {
        return Inertia::render('appuser/KycUploadForm', ['userId' => $userId, 'kycType' => $kycType]);
    }
    public function index()
    {
        $kycDocuments = KycDocument::all();
        return response()->json(['kyc_documents' => $kycDocuments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'kyc_type' => 'required|string',
            'document_type' => 'required|string',
            'document_number' => 'required|string',
            'front_image' => 'required|image',
            'back_image' => 'required|image',
            'status' => 'required|string|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        $frontImagePath = $request->file('front_image')->store('kyc_documents');
        $backImagePath = $request->file('back_image')->store('kyc_documents');

        KycDocument::create([
            'user_id' => $request->user_id,
            'kyc_type' => $request->kyc_type,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'front_image' => $frontImagePath,
            'back_image' => $backImagePath,
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['message' => 'KYC document uploaded successfully']);
    }

    public function show($id)
    {
        $kycDocument = KycDocument::findOrFail($id);
        return response()->json(['kyc_document' => $kycDocument]);
    }

    public function verify(Request $request, $id)
    {
        $kycDocument = KycDocument::findOrFail($id);
        $kycDocument->status = 'approved';
        $kycDocument->verified_by = auth()->id();
        $kycDocument->verified_at = now();
        $kycDocument->save();

        return response()->json(['message' => 'KYC document verified successfully']);
    }

    public function destroy($id)
    {
        $kycDocument = KycDocument::findOrFail($id);
        $kycDocument->delete();

        return response()->json(['message' => 'KYC document deleted successfully']);
    }

    public function upload(Request $request)
    {
        $this->store($request);
    }
}
