<?php
// app/Http/Controllers/TimeRegisterController.php

namespace App\Http\Controllers;

use App\Models\TimeRegister;
use App\Models\Jcr;
use App\Mail\RigSignatureRequest;
use App\Mail\TimeRegisterSignedCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class TimeRegisterController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Base query depends on permission
        if ($user->can('view_any_time::register')) {
            $timeRegisters = TimeRegister::latest()->orderBy('well_handed_over_date', 'desc')->orderBy('well_handed_over_time', 'desc')->paginate(50);
        } else {
            $timeRegisters = $user->timeRegisters()->latest()->orderBy('well_handed_over_date', 'desc')->orderBy('well_handed_over_time', 'desc')->paginate(50);
        }
        return view('time-registers.index', compact('timeRegisters'));
    }


    public function create(Request $request)
    {
        $this->authorize('create', TimeRegister::class);

        $fromJcr = $request->has('from_jcr');
        
        return view('time-registers.create', compact('fromJcr'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', TimeRegister::class);

        $validated = $request->validate([
            'logging_unit_no' => 'required|string|max:255',
            'indent_no' => 'required|string|max:255',
            'well_no' => 'required|string|max:255',
            'rig_no' => 'required|string|max:255',
            
            // Separate date and time validation
            'well_indented_date' => 'required|date',
            'well_indented_time' => 'required|date_format:H:i',
            'well_taken_up_date' => 'nullable|date',
            'well_taken_up_time' => 'nullable|date_format:H:i',
            'well_handed_over_date' => 'nullable|date',
            'well_handed_over_time' => 'nullable|date_format:H:i',
            
            'job_carried_out' => 'required|string',
            'observations_by_logging_chief' => 'required|string',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';

        // Capture logging chief details from logged-in user
        $user = Auth::user();
        $validated['logging_chief_id'] = $user->id;
        $validated['logging_chief_name'] = $user->name;
        $validated['logging_chief_designation'] = $user->designation ?? 'Logging Chief';
        $validated['logging_chief_signature'] = $user->name;
        $validated['logging_chief_signed_at'] = now();

        $timeRegister = TimeRegister::create($validated);

        if ($request->has('save_draft')) {
            $message = 'Time Register saved as draft successfully.';
            
            // If from JCR creation, redirect back
            if ($request->has('from_jcr')) {
                return redirect()->route('jcr.create')
                    ->with('success', $message)
                    ->with('draft_time_register_id', $timeRegister->id);
            }
            
            return redirect()->route('time-registers.index')
                ->with('success', $message);
        }

        // For preview - capture logging chief details and signature
        return $this->savePreview($timeRegister, $request->logging_chief_signature);
    }

    public function preview(TimeRegister $timeRegister)
    {
        if ($timeRegister->is_final_submitted) {
            return redirect()->route('time-registers.show', $timeRegister);
        }

        return view('time-registers.preview', compact('timeRegister'));
    }

    public function savePreview(TimeRegister $timeRegister, $signatureData)
    {
        // Capture logging chief details from logged-in user
        $user = Auth::user();
        
        $timeRegister->update([
            'logging_chief_id' => $user->id,
            'logging_chief_name' => $user->name,
            'logging_chief_designation' => $user->designation ?? 'Logging Chief',
            'logging_chief_signature' => $signatureData,
            'logging_chief_signed_at' => now(),
            'status' => 'preview',
        ]);

        return redirect()->route('time-registers.preview', $timeRegister)
            ->with('success', 'Please review the details and provide rig representative email for final submission.');
    }

    public function finalSubmit(Request $request, TimeRegister $timeRegister)
    {
        if ($timeRegister->is_final_submitted) {
            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'This Time Register is already final submitted.');
        }

        $validated = $request->validate([
            'rig_representative_email' => 'required|email',
        ]);

        // Send email to rig representative
        Mail::to($validated['rig_representative_email'])->send(new RigSignatureRequest($timeRegister));

        // Update with rig representative email and final submission details
        $timeRegister->update([
            'rig_representative_email' => $validated['rig_representative_email'],
            'status' => 'pending_signature',
            'is_final_submitted' => true,
            'final_submitted_at' => now(),
        ]);

        $message = 'Time Register final submitted successfully. Signature request sent to rig representative.';

        // If this was from a JCR context
        if ($request->has('from_jcr')) {
            return redirect()->route('jcr.create')
                ->with('success', $message)
                ->with('time_register_id', $timeRegister->id);
        }

        return redirect()->route('time-registers.show', $timeRegister)
            ->with('success', $message);
    }

    public function resend(TimeRegister $timeRegister)
    {
        if (!$timeRegister->rig_representative_email) {
            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'No rig representative email is set for this Time Register.');
        }

        try {
            Mail::to($timeRegister->rig_representative_email)->send(new RigSignatureRequest($timeRegister));

            return redirect()->route('time-registers.show', $timeRegister)
                ->with('success', 'Signature request resent to rig representative.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend rig signature request: ' . $e->getMessage());

            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'Failed to resend signature request email.');
        }
    }

    public function resendSignedCopy(TimeRegister $timeRegister)
    {
        if (!$timeRegister->rig_representative_email) {
            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'No rig representative email is set for this Time Register.');
        }

        if (!$timeRegister->rig_representative_signature) {
            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'This Time Register has not been signed by the rig representative yet.');
        }

        try {
            Mail::to($timeRegister->rig_representative_email)->send(new TimeRegisterSignedCopy($timeRegister));

            return redirect()->route('time-registers.show', $timeRegister)
                ->with('success', 'Signed Time Register copy resent to rig representative.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend signed time register copy: ' . $e->getMessage());

            return redirect()->route('time-registers.show', $timeRegister)
                ->with('error', 'Failed to resend signed time register copy email.');
        }
    }

    public function show(TimeRegister $timeRegister)
    {
        $timeRegister->load('loggingChief', 'creator');
        return view('time-registers.show', compact('timeRegister'));
    }

    public function edit(TimeRegister $timeRegister)
    {
        $this->authorize('update', $timeRegister);

        return view('time-registers.edit', compact('timeRegister'));
    }

    public function update(Request $request, TimeRegister $timeRegister)
    {
        $this->authorize('update', $timeRegister);

        $validated = $request->validate([
            'logging_unit_no' => 'required|string|max:255',
            'indent_no' => 'required|string|max:255',
            'well_no' => 'required|string|max:255',
            'rig_no' => 'required|string|max:255',
            
            // Separate date and time validation
            'well_indented_date' => 'required|date',
            'well_indented_time' => 'required|date_format:H:i',
            'well_taken_up_date' => 'nullable|date',
            'well_taken_up_time' => 'nullable|date_format:H:i',
            'well_handed_over_date' => 'nullable|date',
            'well_handed_over_time' => 'nullable|date_format:H:i',
            
            'job_carried_out' => 'required|string',
            'observations_by_logging_chief' => 'required|string',
        ]);
        $timeRegister->update($validated);
        
        if ($request->has('save_draft')) {
            $message = 'Time Register draft updated successfully.';
            
            return redirect()->route('time-registers.index')
            ->with('success', $message);
        }
        
        // For preview - capture logging chief details and signature
        // dd($validated);
        if ($request->has('logging_chief_signature')) {
            return $this->savePreview($timeRegister, $request->logging_chief_signature);
        }

        return redirect()->route('time-registers.preview', $timeRegister)
            ->with('success', 'Please review the details and provide rig representative email for final submission.');
    }

    // public function destroy(TimeRegister $timeRegister)
    // {
    //     if (!$timeRegister->canBeEditedBy(Auth::user())) {
    //         return redirect()->route('time-registers.index')
    //             ->with('error', 'You are not authorized to delete this Time Register.');
    //     }

    //     $timeRegister->delete();

    //     return redirect()->route('time-registers.index')
    //         ->with('success', 'Time Register deleted successfully.');
    // }

    // Rig Signature Methods
    public function rigSignatureForm($token)
    {
        $timeRegister = TimeRegister::where('signature_token', $token)->firstOrFail();
        
        if ($timeRegister->rig_representative_signature) {
            return view('time-registers.signature-already-provided');
        }

        return view('time-registers.rig-signature', compact('timeRegister'));
    }

    public function storeRigSignature(Request $request, $token)
    {
        $timeRegister = TimeRegister::where('signature_token', $token)->firstOrFail();

        $validated = $request->validate([
            'rig_representative_observations' => 'required|string',
            'rig_representative_name' => 'required|string|max:255',
            'rig_representative_designation' => 'required|string|max:255',
        ]);

        $validated['rig_representative_signature'] = $validated['rig_representative_name'];
        $validated['status'] = 'completed';
        $validated['rig_representative_signed_at'] = now();

        $timeRegister->update($validated);

        // Send signed copy PDF via email to the rig representative
        try {
            Mail::to($timeRegister->rig_representative_email)->send(new TimeRegisterSignedCopy($timeRegister));
        } catch (\Exception $e) {
            \Log::error('Failed to send signed time register copy: ' . $e->getMessage());
            // Don't fail the signature process if email fails
        }

        return view('time-registers.signature-thankyou');
    }
}