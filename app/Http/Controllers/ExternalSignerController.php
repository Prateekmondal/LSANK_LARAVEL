<?php

namespace App\Http\Controllers;

use App\Models\ExplosiveChecklist;
use App\Models\ExternalSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExternalSignerController extends Controller
{
    public function show($checklistId)
    {
        $checklist = ExplosiveChecklist::findOrFail($checklistId);
        
        if ($checklist->externalSignature()->exists()) {
            return view('external-signer.already-signed');
        }

        return view('external-signer.form', compact('checklist'));
    }

    public function store(Request $request, $checklistId)
    {
        $checklist = ExplosiveChecklist::findOrFail($checklistId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'cpf_no' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // dd($checklist->id);
        // Create external signature
        ExternalSignature::create([
            'explosive_checklist_id' => $checklist->id,
            'name' => $request->name,
            'designation' => $request->designation,
            'cpf_no' => $request->cpf_no,
            'email' => $request->email,
            'signed_at' => now(),
        ]);

        // Update checklist status
        $checklist->update([
            'external_sign_status' => 'completed',
            'status' => $checklist->isFullySigned() ? 'signed' : 'completed',
        ]);

        return view('external-signer.thank-you');
    }
}