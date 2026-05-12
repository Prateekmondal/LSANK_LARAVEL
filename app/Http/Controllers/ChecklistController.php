<?php
//suppose a, b,c checklists as one group. during selection of checklist to be linked with jcr user should create the remaing one or more checklists according to the order a>b>c. user will get pop up for the checklists to be created and will be redirected from the pop up itself. also for multiple missing checklist for a group of checklists user will get info as a pop up about the remaining checklist of the group to be created and will be redirected through the pop up itself or manually. also as soon as the group of checklist will be complete user will be redirected to the add JCR page.
namespace App\Http\Controllers;

use App\Models\ExplosiveChecklist;
use App\Models\ChecklistForward;
use App\Models\Jcr;
use App\Models\User;
use App\Models\loggingUnit;
use App\Notifications\ChecklistForwardedNotification;
use App\Notifications\ChecklistApprovalNotification;
use App\Notifications\ExternalSignerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->can('view_any_explosive::checklist')) {
            $checklists = ExplosiveChecklist::with(['creator', 'signatures.user', 'externalSignature'])
                ->orderBy('date', 'desc')
                ->paginate(10);
        } else {
            $checklists = ExplosiveChecklist::where(function ($query) use ($user) {
                // Checklists created by user
                $query->where('creator_id', $user->id)
                    // Checklists signed by user
                    ->orWhereHas('signatures', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    // Checklists forwarded to user
                    ->orWhereHas('forwards', function ($q) use ($user) {
                        $q->where('to_user_id', $user->id);
                    });
            })
                ->with(['creator', 'signatures.user', 'externalSignature'])
                ->orderBy('date', 'desc')
                ->paginate(10);
        }

        return view('checklists.index', compact('checklists'));
    }

    public function create($type, Request $request)
    {
        $this->authorize('create', ExplosiveChecklist::class);

        $types = ['a' => 'Pre-Departure', 'b' => 'On-Site', 'c' => 'Upon-Arrival'];
        $unitNos = loggingUnit::pluck('loggingUnit')->toArray();

        if (!array_key_exists($type, $types)) {
            abort(404);
        }

        return view('checklists.create', [
            'type' => $type,
            'title' => $types[$type] . ' Checklist',
            'well_no' => $request->input('well_no'),
            'date' => $request->input('date'),
            'unitNos' => $unitNos,
        ]);
    }

    public function store(Request $request, $type)
    {
        $this->authorize('create', ExplosiveChecklist::class);

        // dd($request->all());
        $validated = $request->validate([
            'jcr_id' => 'nullable',
            'well_no' => 'required|string',
            'date' => 'required|date',
            'logging_unit_no' => 'required|string',
            'job_type' => 'nullable|string',
            'perf_interval' => 'nullable|string',
            'rig' => 'nullable|string',
            'checklist_data' => 'required|array',
        ]);
        
        $checklist = ExplosiveChecklist::create(
            $validated + [
                'type' => $type,
                'creator_id' => auth()->id(),
                'status' => 'draft',
            ]
        );

        return redirect()->route('checklists.preview', $checklist->id);
    }

    public function show(ExplosiveChecklist $checklist)
    {
        // dd($checklist);
        return view('checklists.show', compact('checklist'));
    }

    public function edit(ExplosiveChecklist $checklist, $type)
    {
        $this->authorize('update', $checklist);
        $unitNos = loggingUnit::pluck('loggingUnit')->toArray();

        // Check if checklist is editable (draft status)
        // if ($checklist->status !== 'draft') {
        //     return redirect()->route('checklists.show', $checklist->id)
        //         ->with('error', 'Only draft checklists can be edited.');
        // }

        return view('checklists.edit', [
            'checklist' => $checklist,
            'title' => $checklist->type_name . ' Checklist',
            'unitNos' => $unitNos,
        ]);
    }

    public function update(Request $request, ExplosiveChecklist $checklist)
    {
        $this->authorize('update', $checklist);

        // Check if checklist is editable (draft status)
        // if ($checklist->status !== 'draft') {
        //     return redirect()->route('checklists.show', $checklist->id)
        //     ->with('error', 'Only draft checklists can be edited.');
        // }
        
        $validated = $request->validate([
            'well_no' => 'required|string',
            'date' => 'required|date',
            'logging_unit_no' => 'required|string',
            'job_type' => 'nullable|string',
            'perf_interval' => 'nullable|string',
            'rig' => 'nullable|string',
            'items' => 'required|array',
        ]);
        if (!$checklist['job_type'] && !$checklist['perf_interval'] && !$checklist['rig']) {
            $validated['job_type'] = NULL;
            $validated['perf_interval'] = NULL;
            $validated['rig'] = NULL;
        }
        // dd($validated);
        $checklist->update([
            'well_no' => $validated['well_no'],
            'date' => $validated['date'],
            'logging_unit_no' => $validated['logging_unit_no'],
            'job_type' => $validated['job_type'],
            'perf_interval' => $validated['perf_interval'],
            'rig' => $validated['rig'],
            'checklist_data' => $validated['items'],
        ]);

        return redirect()->route('checklists.show', $checklist->id)
            ->with('success', 'Checklist updated successfully!');
    }

    public function destroy(ExplosiveChecklist $checklist)
    {
        $this->authorize('delete', $checklist);

        // Check if checklist is deletable (draft status)
        if ($checklist->status !== 'draft') {
            return redirect()->route('checklists.show', $checklist->id)
                ->with('error', 'Only draft checklists can be deleted.');
        }

        $checklist->delete();

        return redirect()->route('checklists.index')
            ->with('success', 'Checklist deleted successfully!');
    }

    public function forceEdit(ExplosiveChecklist $checklist)
    {
        $this->authorize('forceEdit', ExplosiveChecklist::class);

        return view('checklists.force-edit', compact('checklist'));
    }

    public function forceUpdate(Request $request, ExplosiveChecklist $checklist)
    {
        $this->authorize('forceEdit', ExplosiveChecklist::class);

        $validated = $request->validate([
            'well_no' => 'required|string',
            'date' => 'required|date',
            'logging_unit_no' => 'nullable|string',
            'job_type' => 'required|string',
            'items' => 'required|array',
            'admin_notes' => 'required|string',
        ]);
        $checklist->update($validated);

        // Log the admin override action
        activity()
            ->causedBy(auth()->user())
            ->performedOn($checklist)
            ->withProperties(['admin_notes' => $validated['admin_notes']])
            ->log('admin_override');

        return redirect()->route('checklists.show', $checklist->id)
            ->with('success', 'Checklist updated by admin!');
    }

    public function preview(ExplosiveChecklist $checklist)
    {
        $users = User::orderBy('seniority')->where('status', '=', 1)->where('id', '!=', auth()->id())->get();
        // dd($checklist);
        if ($checklist->status !== 'draft') {
            return redirect()->route('checklists.show', $checklist->id)
                ->with('error', 'Only draft checklists can be previewed.');
        }
        return view('checklists.preview', compact('checklist', 'users'));
    }

    public function confirm(Request $request, ExplosiveChecklist $checklist)
    {
        // dd($request);
        $validated = $request->validate([
            'approver_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value == auth()->id()) {
                        $fail('You cannot select yourself as approver.');
                    }
                },
            ],
            'comments' => 'nullable|string',
            'external_email' => 'nullable|email',
        ]);
        // dd($validated);
        // Creator signs
        $checklist->signatures()->create($validated + [
            'user_id' => auth()->id(),
            'signature_type' => 'creator',
            'signed_at' => now(),
        ]);

        // Update checklist status
        $checklist->update([
            'sign_status' => 'partially_signed',
            'status' => 'completed',
            'external_sign_status' => 'pending',
        ]);

        if ($checklist->type === 'b') {
            $external_signer = $validated['external_email'];
            $this->sendToExternalSigner($request, $checklist);
            // For Checklist B, set external sign status to sent
            $checklist->update([
                'external_sign_status' => 'sent',
            ]);
        } else {
            // For other checklists, set external sign status to not_required
            // Forward to selected approver
            $approver = User::find($validated['approver_id']);
            $this->forwardToApprover($checklist, $approver, $validated['comments']);
        }

        // After creating, check if all group checklists exist
        $wellName = $checklist['well_no'];
        $jobDate = $checklist['date'];
        $creatorId = auth()->id();

        $types = ['a', 'b', 'c'];
        $existing = ExplosiveChecklist::where('well_no', $wellName)
            ->where('date', $jobDate)
            ->where('creator_id', $creatorId)
            ->pluck('type')
            ->toArray();

        $missing = array_diff($types, $existing);

        if (empty($missing)) {
            // All checklists present, redirect to JCR creation
            return redirect()->route('jcr.create');
        }

        // Otherwise, redirect to checklist index or show message
        return redirect()->route('checklists.show', $checklist->id)
            ->with('success', 'Checklist confirmed successfully! Approver has been notified. Please complete remaining checklists.');
    }

    protected function forwardToApprover(ExplosiveChecklist $checklist, User $approver, $comments = null)
    {
        // Create forward record
        $checklist->forwards()->create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $approver->id,
            'message' => 'Please approve this checklist',
            'purpose' => 'approval',
            'comments' => $comments,
        ]);
        // dd($checklist->forwards());

        // Send notification
        $approver->notify(new ChecklistApprovalNotification($checklist, auth()->user()));
    }

    public function approve(Request $request, ExplosiveChecklist $checklist)
    {
        if ($checklist->needsApproverSignature()) {
            $checklist->signatures()->create([
                'user_id' => Auth::id(),
                'signature_type' => 'approver',
                'signed_at' => now(),
                'comments' => $request->input('comments'),
            ]);

            // Update checklist status
            $checklist->update([
                'sign_status' => 'fully_signed',
                'status' => 'signed',
                'external_sign_status' => 'completed',
            ]);

            return redirect()->route('checklists.show', $checklist->id)
                ->with('success', 'Checklist approved successfully!');
        }

        return redirect()->route('checklists.show', $checklist->id)
            ->with('error', 'Checklist already has approver signature');
    }


    public function linkToJcr(Request $request, ExplosiveChecklist $checklist)
    {
        $request->validate(['jcr_id' => 'required|exists:job_completion_reports,id']);

        $this->authorize('update', $checklist);

        // Prevent linking if already linked
        if ($checklist->jcr_id) {
            return back()->with('error', 'Checklist already linked to a JCR.');
        }

        $checklist->update(['jcr_id' => $request->jcr_id]);

        // Optionally: Notify user(s) or log activity

        return back()->with('success', 'Checklist linked to JCR successfully!');
    }

    public function checkGroupCompletion(Request $request)
    {
        $wellName = $request->input('well_no');
        $jobDate = $request->input('job_date');
        $creatorId = auth()->id();

        $types = ['a', 'b', 'c'];
        $existing = ExplosiveChecklist::where('well_no', $wellName)
            ->where('date', $jobDate)
            ->pluck('type')
            ->toArray();

        $missing = array_diff($types, $existing);
        // dd($missing);
        return response()->json([
            'missing' => $missing,
            'all_present' => empty($missing),
        ]);
    }

    // Add this method to handle sending to external signer
    public function sendToExternalSigner(Request $request, ExplosiveChecklist $checklist)
    {
        if ($checklist->type !== 'b') {
            return back()->with('error', 'Only Checklist B requires external signature');
        }

        $validated = $request->validate([
            'external_email' => 'required|email',
        ]);
        // Send notification to external signer
        \Notification::route('mail', $validated['external_email'])
            ->notify(new ExternalSignerNotification($checklist));

        // Update checklist status
        $checklist->update([
            'external_sign_status' => 'sent',
        ]);
        // dd($validated);

        return back()->with('success', 'Checklist has been sent to external signer '.$validated['external_email'].'.');
    }
}