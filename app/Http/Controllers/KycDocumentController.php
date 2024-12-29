<?php

namespace App\Http\Controllers;

use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycDocumentController extends Controller
{
    public function index()
    {
        $documents = KycDocument::with(['user', 'verifier'])->latest()->get();
        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kyc_type' => 'required|in:vendor,rider',
            'document_type' => 'required|string',
            'document_number' => 'required|string',
            'front_image' => 'required|image|max:2048',
            'back_image' => 'nullable|image|max:2048',
            'expiry_date' => 'nullable|date'
        ]);

        $frontImagePath = $request->file('front_image')->store('kyc_documents', 'public');
        $backImagePath = null;

        if ($request->hasFile('back_image')) {
            $backImagePath = $request->file('back_image')->store('kyc_documents', 'public');
        }

        $document = KycDocument::create([
            'user_id' => Auth::id(),
            'kyc_type' => $request->kyc_type,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'front_image' => $frontImagePath,
            'back_image' => $backImagePath,
            'expiry_date' => $request->expiry_date,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document
        ]);
    }

    public function verify(Request $request, KycDocument $document)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected'
        ]);

        $document->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => Auth::id(),
            'verified_at' => now()
        ]);

        return response()->json([
            'message' => 'Document ' . $request->status . ' successfully',
            'document' => $document
        ]);
    }

    public function show(KycDocument $document)
    {
        return response()->json($document->load(['user', 'verifier']));
    }

    public function destroy(KycDocument $document)
    {
        if ($document->front_image) {
            Storage::disk('public')->delete($document->front_image);
        }
        if ($document->back_image) {
            Storage::disk('public')->delete($document->back_image);
        }

        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully'
        ]);
    }
}
