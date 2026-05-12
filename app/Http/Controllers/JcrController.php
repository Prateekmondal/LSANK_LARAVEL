<?php

namespace App\Http\Controllers;

use App\Models\Jcr;
use App\Models\User;
use App\Models\loggingUnit;
use App\Models\ExplosiveChecklist;
use App\Models\timeRegister;
use App\Services\SapService;
use App\Notifications\JcrAssignedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class JcrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base query depends on permission
        if ($user->can('view_any_jcr')) {
            $query = Jcr::with(['users', 'logs', 'explosives']);
        } else {
            $query = $user->jcrs()->with(['users', 'logs', 'explosives']);
        }

        // Month filter (expects format YYYY-MM)
        $monthParam = trim((string) $request->input('month', ''));
        if ($monthParam !== '') {
            try {
                $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
                $start = $selectedMonth->copy()->startOfMonth()->format('Y-m-d');
                $end = $selectedMonth->copy()->endOfMonth()->format('Y-m-d');
                $query = $query->whereBetween('arrivalOffice_date', [$start, $end]);
            } catch (\Exception $e) {
                // ignore invalid month format
            }
        }

        // User-based filter for certain roles
        $allowedRoles = ['Technical_Support_Group', 'party_chief', 'operation_incharge', 'super-admin', 'Head_Logging_Services', 'Location Manager'];
        $userFilter = $request->input('user_id');
        if ($user->hasAnyRole($allowedRoles) && !empty($userFilter)) {
            // Filter by users associated with the JCR (many-to-many relation)
            $query = $query->whereHas('users', function ($q) use ($userFilter) {
                $q->where('users.id', $userFilter);
            });
        }

        // Finalize query
        $jcrs = $query->orderBy('arrivalOffice_date', 'desc')
            ->orderBy('arrivalOffice_time', 'desc')
            ->paginate(50)
            ->appends($request->only('month', 'user_id'));

        // Provide users list to the view if the current user can filter by users
        $filterUsers = collect();
        if ($user->hasAnyRole($allowedRoles)) {
            $filterUsers = User::where('status', 1)->orderBy('seniority')->get();
        }

        return view('jcr.index', compact('jcrs', 'filterUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Jcr::class);

        $users = User::orderBy('seniority')->get()->where('status', 1);
        $unitNos = loggingUnit::pluck('loggingUnit')->toArray();
        $unlinkedChecklists = ExplosiveChecklist::whereNull('jcr_id')
            ->get();

        // Group unlinked checklists by type for the view
        $groupedUnlinkedChecklists = [
            'a' => $unlinkedChecklists->where('type', 'a')->values(),
            'b' => $unlinkedChecklists->where('type', 'b')->values(),
            'c' => $unlinkedChecklists->where('type', 'c')->values(),
        ];

        // Get available time registers for linking (exclude those whose job already has all three checklist types linked)
        $availableTimeRegisters = TimeRegister::availableForLinking()->withoutFullyLinkedChecklists()->with('jcrs')->get();
        // dd($unlinkedChecklists);
        return view('jcr.create', compact('users', 'unitNos', 'groupedUnlinkedChecklists', 'availableTimeRegisters'));
    }
    
    public function view()
    {
        $user = Auth::user();
        if ($user->can('view_any_jcr')) {
            $jcrs = Jcr::with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')->paginate(50);
        } else {
            $jcrs = $user->jcrs()->with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')->paginate(50);
        }
        // dd($jcrs);
        return view("viewjcr", ['jcrs' => $jcrs]);
    }
    public function dashboardView(Request $request)
    {
        $user = Auth::user();

        // Determine selected month (format: Y-m) from query param. If none provided, show ALL jobs.
        $monthParam = trim((string) $request->input('month', ''));
        if ($monthParam !== '') {
            try {
                $selectedMonth = \Carbon\Carbon::createFromFormat('Y-m', $monthParam);
            } catch (\Exception $e) {
                $selectedMonth = null;
            }
        } else {
            $selectedMonth = null;
        }

        if ($selectedMonth) {
            $start = $selectedMonth->copy()->startOfMonth()->format('Y-m-d');
            $end = $selectedMonth->copy()->endOfMonth()->format('Y-m-d');

            // Counts limited to the selected month
            $ch_count = $user->jcrs()->whereBetween('arrivalOffice_date', [$start, $end])->where('logType', 'CH')->count();
            $oh_count = $user->jcrs()->whereBetween('arrivalOffice_date', [$start, $end])->where('logType', 'OH')->count();
            $pl_count = $user->jcrs()->whereBetween('arrivalOffice_date', [$start, $end])->where('logType', 'PL')->count();
            $total_count = $user->jcrs()->whereBetween('arrivalOffice_date', [$start, $end])->count();

            // Paginate jobs for the selected month
            $jcrs = $user->jcrs()->with(['users', 'logs', 'explosives'])
                ->whereBetween('arrivalOffice_date', [$start, $end])
                ->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')
                ->paginate(50)
                ->appends($request->only('month'));

            // Dates for calendar highlighting (Y-m-d format)
            $jobDates = $user->jcrs()->whereBetween('arrivalOffice_date', [$start, $end])
                ->pluck('arrivalOffice_date')
                ->map(function ($d) {
                    return \Carbon\Carbon::parse($d)->format('Y-m-d');
                });
        } else {
            // No month selected: return all jobs and counts
            $ch_count = $user->jcrs()->where('logType', 'CH')->count();
            $oh_count = $user->jcrs()->where('logType', 'OH')->count();
            $pl_count = $user->jcrs()->where('logType', 'PL')->count();
            $total_count = $user->jcrs()->count();

            $jcrs = $user->jcrs()->with(['users', 'logs', 'explosives'])
                ->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')
                ->paginate(50);

            $jobDates = $user->jcrs()->pluck('arrivalOffice_date')
                ->map(function ($d) {
                    return \Carbon\Carbon::parse($d)->format('Y-m-d');
                });
        }

        // Prepare financial year statistics for charts (FY starting April - ending March)
        $now = \Carbon\Carbon::now();

        if ($now->month >= 4) {
            $fyStart = \Carbon\Carbon::create($now->year, 4, 1);
        } else {
            $fyStart = \Carbon\Carbon::create($now->year - 1, 4, 1);
        }
        $fyEnd = $fyStart->copy()->addYear()->subDay();
        $prevFyStart = $fyStart->copy()->subYear();
        $prevFyEnd = $fyEnd->copy()->subYear();

        // Labels (Apr -> Mar)
        $labels = [];
        for ($m = 0; $m < 12; $m++) {
            $labels[] = $fyStart->copy()->addMonths($m)->format('M');
        }

        // Counts per month for current and previous financial years
        $currentFyCounts = array_fill(0, 12, 0);
        $previousFyCounts = array_fill(0, 12, 0);

        for ($i = 0; $i < 12; $i++) {
            $s = $fyStart->copy()->addMonths($i)->startOfMonth()->format('Y-m-d');
            $e = $fyStart->copy()->addMonths($i)->endOfMonth()->format('Y-m-d');
            $currentFyCounts[$i] = $user->jcrs()->whereBetween('arrivalOffice_date', [$s, $e])->count();

            $ps = $prevFyStart->copy()->addMonths($i)->startOfMonth()->format('Y-m-d');
            $pe = $prevFyStart->copy()->addMonths($i)->endOfMonth()->format('Y-m-d');
            $previousFyCounts[$i] = $user->jcrs()->whereBetween('arrivalOffice_date', [$ps, $pe])->count();
        }

        // Current and previous calendar months
        $currentMonth = $now->copy();
        $previousMonth = $now->copy()->subMonth();

        $currentFyCurrentMonthCount = 0;
        $currentFyPreviousMonthCount = 0;
        $prevFyCurrentMonthCount = 0;
        $prevFyPreviousMonthCount = 0;

        if ($currentMonth->between($fyStart, $fyEnd)) {
            $idx = $currentMonth->diffInMonths($fyStart);
            $currentFyCurrentMonthCount = $currentFyCounts[$idx] ?? 0;
        }
        if ($previousMonth->between($fyStart, $fyEnd)) {
            $idx2 = $previousMonth->diffInMonths($fyStart);
            $currentFyPreviousMonthCount = $currentFyCounts[$idx2] ?? 0;
        }

        if ($currentMonth->between($prevFyStart, $prevFyEnd)) {
            $idx3 = $currentMonth->diffInMonths($prevFyStart);
            $prevFyCurrentMonthCount = $previousFyCounts[$idx3] ?? 0;
        }
        if ($previousMonth->between($prevFyStart, $prevFyEnd)) {
            $idx4 = $previousMonth->diffInMonths($prevFyStart);
            $prevFyPreviousMonthCount = $previousFyCounts[$idx4] ?? 0;
        }

        $currentFySummary = [
            'label' => $fyStart->format('Y').' - '.$fyEnd->format('Y'),
            'total' => array_sum($currentFyCounts),
            'current_month_label' => $currentMonth->format('F Y'),
            'current_month_count' => $currentFyCurrentMonthCount,
            'previous_month_label' => $previousMonth->format('F Y'),
            'previous_month_count' => $currentFyPreviousMonthCount,
        ];

        $previousFySummary = [
            'label' => $prevFyStart->format('Y').' - '.$prevFyEnd->format('Y'),
            'total' => array_sum($previousFyCounts),
            'current_month_label' => $currentMonth->format('F Y'),
            'current_month_count' => $prevFyCurrentMonthCount,
            'previous_month_label' => $previousMonth->format('F Y'),
            'previous_month_count' => $prevFyPreviousMonthCount,
        ];

        return view("dashboard", [
            'jcrs' => $jcrs,
            'user' => $user,
            'ch' => $ch_count,
            'oh' => $oh_count,
            'pl' => $pl_count,
            'total' => $total_count,
            'selectedMonth' => $selectedMonth,
            'jobDates' => $jobDates,
            'chartLabels' => $labels,
            'currentFyCounts' => $currentFyCounts,
            'previousFyCounts' => $previousFyCounts,
            'currentFySummary' => $currentFySummary,
            'previousFySummary' => $previousFySummary,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Jcr::class);

        $validated = $this->validateRequest($request);

        $action = $request->input('action');
        $isDraft = $action === 'save_draft';

        DB::beginTransaction();
        try {
            // dd($validated['explosive']);
            // $jcrData = $validated['jcr'];
            $jcrData = array_diff_key($validated, array_flip(['personnel', 'logrecorded', 'explosive']));
            $jcrData['creator_id'] = Auth::id();
            $jcrData['final_submit'] = $isDraft;
            $jcrData['status'] = Jcr::STATUS_DRAFT;

            // Ensure role edit flags exist for newly created records
            $jcrData['party_chief_edited'] = false;
            $jcrData['operation_incharge_edited'] = false;

            // Check if time register is available for linking
            $timeRegister = TimeRegister::findOrFail($validated['time_register_id']);
            
            if (!$timeRegister->isAvailableForLinking()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected Time Register is not available for linking.');
            }

            // Create JCR with linked time register
            $jcrData['time_register_linked'] = true;

            $jcr = Jcr::create($jcrData);

            // Sync personnel
            if (isset($validated['personnel'])) {
                $personnelIds = collect($validated['personnel'])->pluck('user_id')->toArray();
                $jcr->users()->sync($personnelIds);
            }

            // Create logs
            if (isset($validated['logrecorded'])) {
                $jcr->logs()->createMany($validated['logrecorded']);
            }
            // dd($validated);
            // Create explosives
            if (isset($validated['explosive'])) {
                $jcr->explosives()->createMany($validated['explosive']);
            }

            // Attach selected checklists (only those owned by the creator and currently unlinked)
            if (isset($validated['checklist_ids']) && is_array($validated['checklist_ids'])) {
                ExplosiveChecklist::whereIn('id', $validated['checklist_ids'])
                    ->whereNull('jcr_id')
                    ->update(['jcr_id' => $jcr->id]);
            }

            DB::commit();

            if ($isDraft) {
                return redirect()->route('jcr.index')
                    ->with('success', 'JCR saved as draft successfully.');
            }

            return redirect()->route('jcr.preview', $jcr->id)
                ->with('success', 'JCR created successfully. Please review before final submission.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create JCR: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jcr $jcr)
    {
        $user = Auth::user();

        // Delegate authorization to the policy
        $this->authorize('update', $jcr);

        // Determine if current edit should set the one-time flags
        $canPartyChiefEdit = $user->hasRole('party_chief') && !$jcr->party_chief_edited && $jcr->party_chief_id == $user->id;
        $canOperationInchargeEdit = $user->hasRole('operation_incharge') && !$jcr->operation_incharge_edited && $jcr->operation_incharge_id == $user->id;

        $validated = $this->validateRequest($request, $jcr);
        
        $action = $request->input('action');
        $isDraft = $action === 'save_draft';
        
        DB::beginTransaction();
        try {
            $jcrData = array_diff_key($validated, array_flip(['personnel', 'logrecorded', 'explosive']));
            $jcrData['final_submit'] = $isDraft;

            if ($isDraft) {
                // If saving as draft, reset signatures and assigned party chief
                $jcrData['status'] = Jcr::STATUS_DRAFT;
                $jcrData['creator_signature'] = null;
                $jcrData['creator_signed_at'] = null;
                
                $jcrData['party_chief_signature'] = null;
                $jcrData['party_chief_signed_at'] = null;
                $jcrData['party_chief_id'] = null;
                $jcrData['operation_incharge_signature'] = null;
                $jcrData['operation_incharge_signed_at'] = null;
                $jcrData['operation_incharge_id'] = null;
                
                // Reset edit flags
                $jcrData['party_chief_edited'] = false;
                $jcrData['operation_incharge_edited'] = false;
            }
            
            // Mark edit flag for role
            if ($canPartyChiefEdit) {
                $jcrData['party_chief_edited'] = true;
            }
            if ($canOperationInchargeEdit) {
                $jcrData['operation_incharge_edited'] = true;
            }
            
            $jcr->update($jcrData);
            
            // Sync personnel (allow removing all personnel by syncing an empty array when none submitted)
            $personnelIds = [];
            if (isset($validated['personnel']) && is_array($validated['personnel'])) {
                $personnelIds = collect($validated['personnel'])
                    ->pluck('user_id')
                    ->filter()
                    ->unique()
                    ->toArray();
            }

            $jcr->users()->sync($personnelIds);
            
            // Update or create logs
            if (isset($validated['logrecorded'])) {
                $jcr->logs()->delete();
                $jcr->logs()->createMany($validated['logrecorded']);
            }
            // dd($validated);
            // Update or create explosives
            if (isset($validated['explosive'])) {
                $jcr->explosives()->delete();
                $jcr->explosives()->createMany($validated['explosive']);
            }
            
            // Sync checklists: detach previously linked checklists and attach selected ones
            if (isset($validated['checklist_ids']) && is_array($validated['checklist_ids'])) {
                // detach any checklists currently linked to this JCR
                ExplosiveChecklist::where('jcr_id', $jcr->id)->update(['jcr_id' => null]);
                
                // attach provided checklists (limit to creator-owned ones)
                ExplosiveChecklist::whereIn('id', $validated['checklist_ids'])
                ->where('creator_id', Auth::id())
                ->update(['jcr_id' => $jcr->id]);
            }
            
            // Check if time register is available for linking (excluding current one)
            $timeRegister = TimeRegister::findOrFail($validated['time_register_id']);
            
            if (!$timeRegister->isAvailableForLinking() && $timeRegister->id !== $jcr->time_register_id) {
                // dd($validated);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected Time Register is not available for linking.');
            }

            DB::commit();

            if ($isDraft) {
                return redirect()->route('jcr.index')
                    ->with('success', 'JCR draft updated successfully.');
            }

            return redirect()->route('jcr.preview', $jcr->id)
                ->with('success', 'JCR updated successfully. Please review before final submission.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update JCR: ' . $e->getMessage());
        }
    }

    /**
     * Show preview before final submission
     */
    public function preview(Jcr $jcr)
    {
        if ($jcr->final_submit && $jcr->party_chief_id) {
            return redirect()->route('jcr.show', $jcr->id);
        }

        $jcr->load(['users', 'logs', 'explosives', 'checklists']);

        // If the current user is the creator, provide a list of available party chiefs
        $partyChiefs = collect();
        if (Auth::check()) {
            // assumes Spatie role 'party_chief' exists
            $partyChiefs = User::role('party_chief')->where('status', 1)->get();
        }
        // dd(compact('jcr', 'partyChiefs'));
        return view('jcr.preview', compact('jcr', 'partyChiefs'));
    }

    /**
     * Assign a party chief (only creator can assign) and notify them.
     */
    public function assignPartyChief(Request $request, Jcr $jcr)
    {
        $user = Auth::user();

        // Only creator can assign
        if (!$user) {
            return redirect()->route('jcr.preview', $jcr->id)
                ->with('error', 'Only the creator can assign a Party Chief.');
        }

        $validated = $request->validate([
            'party_chief_id' => ['required','integer','exists:users,id'],
        ]);

        $partyChief = User::find($validated['party_chief_id']);

        // optional: ensure selected user has the party_chief role
        if (!method_exists($partyChief, 'hasRole') || !$partyChief->hasRole('party_chief')) {
            return redirect()->route('jcr.preview', $jcr->id)
                ->with('error', 'Selected user is not a Party Chief.');
        }

        DB::beginTransaction();
        try {
            $jcr->update([
                'party_chief_id' => $partyChief->id,
                // ensure assigned party chief has not yet edited/sign
                'party_chief_edited' => false,
            ]);

            // send notification
            $partyChief->notify(new JcrAssignedNotification($jcr, $user));

            DB::commit();

            return redirect()->route('jcr.preview', $jcr->id)
                ->with('success', 'Party Chief assigned and notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign Party Chief: ' . $e->getMessage());
        }
    }

    /**
     * Party Chief signature
     */
    public function partyChiefSign(Request $request, Jcr $jcr)
    {
        // Check if user has party chief role
        if (!Auth::user()->hasRole('party_chief')) {
            return redirect()->route('jcr.show', $jcr->id)
                ->with('error', 'You are not authorized to sign as Party Chief.');
        }

        // Ensure only the assigned party chief can sign
        if (!is_null($jcr->party_chief_id) && Auth::id() !== $jcr->party_chief_id) {
            return redirect()->route('jcr.show', $jcr->id)
                ->with('error', 'Only the assigned Party Chief can sign this JCR.');
        }

        DB::beginTransaction();
        try {
            $jcr->update([
                'party_chief_signature' => Auth::user()->name,
                'party_chief_signed_at' => now(),
                'party_chief_id' => Auth::id(),
                'status' => Jcr::STATUS_PENDING_OPERATION_INCHARGE,
                'party_chief_edited' => true,
            ]);

            DB::commit();

            return redirect()->route('jcr.show', ['jcr' => $jcr->id])
                ->with('success', 'Party Chief signature added. Waiting for Operation Incharge signature.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add Party Chief signature: ' . $e->getMessage());
        }
    }

    /**
     * Final submission
     */
    public function submit(Request $request, Jcr $jcr)
    {
        if (Auth::user()->hasAnyRole(['party_chief', 'operation_incharge', 'super-admin'])) {
            $this->assignPartyChief($request, $jcr);
            if ($jcr->creator_id != Auth::id()) {
                $jcr->update(['final_submit' => true, 'status' => Jcr::STATUS_PENDING_PARTY_CHIEF, 'creator_signature' => User::find($jcr->creator_id)->name]);
            } else {
                $jcr->update(['final_submit' => true, 'status' => Jcr::STATUS_PENDING_PARTY_CHIEF, 'creator_signature' => Auth::user()->name, 'creator_signed_at' => now()]);
            }
        }
        else {
            $this->assignPartyChief($request, $jcr);
            $jcr->update(['final_submit' => true, 'status' => Jcr::STATUS_PENDING_PARTY_CHIEF, 'creator_signature' => Auth::user()->name, 'creator_signed_at' => now()]);
        }
        // dd($jcr);
        return redirect()->route('jcr.show', $jcr->id)->with('success', 'JCR submitted successfully.');
    }

    /**
     * Operation Incharge signature
     */
    public function operationInchargeSign(Request $request, Jcr $jcr)
    {
        // Check if user has operation incharge role
        if (!Auth::user()->hasRole('operation_incharge')) {
            return redirect()->route('jcr.show', $jcr->id)
                ->with('error', 'You are not authorized to sign as Operation Incharge.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        DB::beginTransaction();
        try {
            $status = $request->action === 'approve'
                ? Jcr::STATUS_APPROVED
                : Jcr::STATUS_REJECTED;

            if ($status === Jcr::STATUS_REJECTED) {
                // If rejected, clear party chief signature details
                $jcr->update([
                    'party_chief_signature' => null,
                    'party_chief_signed_at' => null,
                    'party_chief_id' => null,
                    'status' => Jcr::STATUS_PENDING_PARTY_CHIEF,
                    'party_chief_edited' => false,
                ]);
            } elseif ($status === Jcr::STATUS_APPROVED) {
                // If approved, ensure party chief signature details are intact
                if (is_null($jcr->party_chief_signature) || is_null($jcr->party_chief_signed_at) || is_null($jcr->party_chief_id)) {
                    throw new \Exception('Cannot approve JCR without Party Chief signature.');
                }
                // Update operation incharge signature details
                else {
                    $jcr->update([
                        'operation_incharge_signature' => Auth::user()->name,
                        'operation_incharge_signed_at' => now(),
                        'operation_incharge_id' => Auth::id(),
                        'status' => $status,
                        'operation_incharge_edited' => true,
                    ]);
                }
            }

            DB::commit();

            $message = $request->action === 'approve' ? 'JCR approved successfully.' : 'JCR rejected.';

            return redirect()->route('jcr.show', ['jcr' => $jcr->id])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process Operation Incharge action: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jcr $jcr)
    {
        // Use policy-based authorization
        $this->authorize('update', $jcr);

        $users = User::all();
        $unitNos = loggingUnit::pluck('loggingUnit')->toArray();
        $unlinkedChecklists = ExplosiveChecklist::whereNull('jcr_id')
            ->get();

            // Group unlinked checklists by type for the edit view
        $groupedUnlinkedChecklists = [
            'a' => $unlinkedChecklists->where('type', 'a')->values(),
            'b' => $unlinkedChecklists->where('type', 'b')->values(),
            'c' => $unlinkedChecklists->where('type', 'c')->values(),
        ];

        // also get checklists already linked to this JCR and group them
        $linkedChecklists = $jcr->checklists()->with('creator')->get();
        $groupedLinkedChecklists = [
            'a' => $linkedChecklists->where('type', 'a')->values(),
            'b' => $linkedChecklists->where('type', 'b')->values(),
            'c' => $linkedChecklists->where('type', 'c')->values(),
        ];

        $availableTimeRegisters = TimeRegister::availableForLinking()->with('jcrs')->get();

        $jcr->load(['users', 'logs', 'explosives', 'checklists', 'timeRegister']);
        return view('jcr.edit', compact('jcr', 'users', 'unitNos', 'unlinkedChecklists', 'groupedUnlinkedChecklists', 'groupedLinkedChecklists', 'availableTimeRegisters'));
    }

    public function show(Jcr $jcr)
    {
        // dd($jcr);
        $jcr->load(['users', 'logs', 'explosives', 'timeRegister.creator']);
        $checklists = $jcr->checklists()->with(['creator', 'signatures.user', 'externalSignature'])->get();

        // Group checklists by type
        $groupedChecklists = [
            'a' => $checklists->where('type', 'a')->first(),
            'b' => $checklists->where('type', 'b')->first(),
            'c' => $checklists->where('type', 'c')->first(),
        ];
        // If the current user is the creator, provide a list of available party chiefs
        $partyChiefs = collect();
        if (Auth::check()) {
            // assumes Spatie role 'party_chief' exists
            $partyChiefs = User::role('party_chief')->where('status', 1)->get();
        }
        // dd($jcr);
        return view('jcr.show', compact('jcr', 'groupedChecklists' , 'partyChiefs'));
    }

    public function print(Jcr $jcr)
    {
        $timeRegister = $jcr->timeRegister()->get()->first();
        $checklists = $jcr->checklists()->with(['creator', 'signatures.user', 'externalSignature'])->get();

        $groupedChecklists = [
            'a' => $checklists->where('type', 'a')->first(),
            'b' => $checklists->where('type', 'b')->first(),
            'c' => $checklists->where('type', 'c')->first(),
        ];
        // Pdf::view('jcr.print', compact('jcr', 'groupedChecklists', 'timeRegister'))->save(storage_path('public/jcr_'.$jcr->id.'.pdf'));
        return view('jcr.print', compact('jcr', 'groupedChecklists', 'timeRegister'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jcr  $jcr
     * @return \Illuminate\Http\Response
     */
    public function show_old(Request $request): RedirectResponse|View
    {
        $users = User::all();
        if (Auth::user()->can('update_jcr')) {
            # code...
            $jcrs = Jcr::with(['users', 'logs', 'explosives'])->get()->where('id', '=', $request->get('id'))->first();
            // dd($jcrs);
            if ($jcrs->final_submitted == 0) {
                return view("editjcr", ['jcrs' => $jcrs, 'users' => $users]);
            } else {
                return redirect()->route('jcr.view');
            }
        } else {
            # code...
            return redirect()->route('jcr.view');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jcr  $jcr
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request): View
    {
        // dd('hello');
        $users = User::all();
        if (Auth::user()->can('view_any_jcr')) {
            $jcrs = Jcr::with(['users', 'logs', 'explosives'])->get()->where('id', '=', $request->get('id'))->first();
        } else {
            $jcrs = Auth::user()->jcrs()->with(['users', 'logs', 'explosives'])->get()->where('id', '=', $request->get('id'))->first();
        }
        // dd($jcrs);
        return view("downloadjcr", ['jcrs' => $jcrs, 'users' => $users]);
    }

    /**
     * Sync explosives for the JCR
     */
    protected function syncExplosives($jcr, array $explosivesData)
    {
        $existingIds = $jcr->explosives()->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($explosivesData as $index => $explosive) {
            if (!empty($explosive['id'] ?? null)) {
                // dd($explosive);
                // Update existing explosive
                $jcr->explosives()->where('id', '=', $explosive['id'])->update([
                    'explosive' => $explosive['explosive'],
                    'issued' => $explosive['issued'],
                    'used' => $explosive['used'],
                    'returned' => $explosive['returned'],
                ]);
                $updatedIds[] = $explosive['id'];
            } elseif (!is_null($explosive['explosive'])) {
                // Create new explosive
                $newExplosive = $jcr->explosives()->create([
                    'explosive' => $explosive['explosive'],
                    'issued' => $explosive['issued'],
                    'used' => $explosive['used'],
                    'returned' => $explosive['returned'],
                ]);
                $updatedIds[] = $newExplosive->id;
            }
        }
        // dd($updatedIds);
    }

    /**
     * Sync logs for the JCR
     */
    protected function syncLogs($jcr, array $logsData)
    {
        $existingIds = $jcr->logs()->pluck('id')->toArray();
        $updatedIds = [];

        // dd($logsData);
        foreach ($logsData as $index => $log) {
            if (!empty($log['id'] ?? null)) {
                // Update existing log
                $jcr->logs()->where('id', '=', $log['id'])->update([
                    'runNo' => $log['runNo'],
                    'logRecorded' => $log['logRecorded'],
                    'bottomDepth' => $log['bottomDepth'],
                    'topDepth' => $log['topDepth'],
                    'toolNo' => $log['toolNo'],
                    'logQuality' => $log['logQuality'],
                    'bottomShotDepth' => $log['bottomShotDepth'],
                    'topShotDepth' => $log['topShotDepth'],
                    'charge' => $log['charge'],
                    'chargeNo' => $log['chargeNo'],
                    'primaChord' => $log['primaChord'],
                    'primaChordQty' => $log['primaChordQty'],
                    'fuse' => $log['fuse'],
                    'fuseNo' => $log['fuseNo'],
                    'fMf' => $log['fMf'],
                ]);
                $updatedIds[] = $log['id'];
            } else {
                // Create new explosive
                $newLog = $jcr->logs()->create([
                    'runNo' => $log['runNo'],
                    'logRecorded' => $log['logRecorded'],
                    'bottomDepth' => $log['bottomDepth'],
                    'topDepth' => $log['topDepth'],
                    'toolNo' => $log['toolNo'],
                    'logQuality' => $log['logQuality'],
                    'bottomShotDepth' => $log['bottomShotDepth'],
                    'topShotDepth' => $log['topShotDepth'],
                    'charge' => $log['charge'],
                    'chargeNo' => $log['chargeNo'],
                    'primaChord' => $log['primaChord'],
                    'primaChordQty' => $log['primaChordQty'],
                    'fuse' => $log['fuse'],
                    'fuseNo' => $log['fuseNo'],
                    'fMf' => $log['fMf'],
                ]);
                $updatedIds[] = $newLog->id;
            }
        }
    }

    // AJAX method to find existing JCRs by wellNo content match
    public function ajaxFindJcrByWellNo(Request $request)
    {
        $request->validate([
            'wellNo' => 'required|string',
        ]);

        $wellNoInput = trim($request->input('wellNo'));
        if ($wellNoInput === '') {
            return response()->json(['data' => []]);
        }

        $matches = Jcr::with('users')
            ->whereRaw('? LIKE CONCAT("%", wellNo, "%")', [$wellNoInput])
            ->orderBy('arrivalOffice_date', 'desc')
            ->limit(12)
            ->get();

        return response()->json(['data' => $matches]);
    }

    // AJAX method to get time register details for modal
    public function getTimeRegisterDetails($id)
    {
        // Fetch time register regardless of whether it is currently available for linking so
        // existing selections continue to work when editing an existing JCR.
        $timeRegister = TimeRegister::find($id);
        
        if (!$timeRegister) {
            return response()->json(['error' => 'Time Register not found'], 404);
        }

        return response()->json([
            'id' => $timeRegister->id,
            'logging_unit_no' => $timeRegister->logging_unit_no,
            'indent_no' => $timeRegister->indent_no,
            'well_no' => $timeRegister->well_no,
            'rig_no' => $timeRegister->rig_no,
            'job_carried_out' => $timeRegister->job_carried_out,
            'well_indented_date' => $timeRegister->well_indented_date ? $timeRegister->well_indented_date->format('Y-m-d') : 'N/A',
            'well_indented_time' => $timeRegister->well_indented_time ?? 'N/A',
            'status' => $timeRegister->status,
            'is_final_submitted' => $timeRegister->is_final_submitted,
            'summary' => $timeRegister->getSelectionSummary(),
            'logging_chief_name' => $timeRegister->logging_chief_name,
            'logging_chief_designation' => $timeRegister->logging_chief_designation,
        ]);
    }

    /**
     * AJAX endpoint to fetch jobs (paginated) optionally filtered by month (format YYYY-MM).
     */
    public function dashboardJobs(Request $request)
    {
        $user = Auth::user();
        $month = $request->query('month'); // expected 'YYYY-MM' or null
        $query = $user->jcrs()->with(['users', 'logs', 'explosives'])
            ->orderBy('arrivalOffice_date', 'desc')
            ->orderBy('arrivalOffice_time', 'desc');

        if ($month) {
            try {
                $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
                $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();
                $query->whereBetween('arrivalOffice_date', [$start, $end]);
            } catch (\Exception $e) {
                // ignore invalid month format -> return all
            }
        }

        $jcrs = $query->paginate(50)->withQueryString();

        $rowsHtml = view('dashboard._jcr_rows', compact('jcrs'))->render();
        $paginationHtml = (string) $jcrs->links('pagination::bootstrap-5');

        return response()->json([
            'rows' => $rowsHtml,
            'pagination' => $paginationHtml,
        ]);
    }

    /**
     * Export filtered jobs to XLSX. month query param optional (YYYY-MM).
     */
    public function exportJobs(Request $request)
    {
        $month = $request->query('month');
        $filename = 'jcrs' . ($month ? "_{$month}" : '') . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new JcrsExport(Auth::id(), $month), $filename);
    }

    /**
     * Validate the request data.
     */
    protected function validateRequest(Request $request, Jcr $jcr = null)
    {
        // Normalize checklist_ids when the form submits a comma-separated string
        $checklistInput = $request->input('checklist_ids', null);
        if (is_string($checklistInput)) {
            $ids = array_filter(array_map('trim', explode(',', $checklistInput)), function ($v) {
                return $v !== '';
            });
            // If no ids, set to null so 'nullable|array' passes
            $request->merge(['checklist_ids' => count($ids) ? $ids : null]);
        }

        $rules = [
            'id' => 'sometimes|numeric',
            'fieldName' => 'required|string',
            'wellNo' => 'required|string',
            'jobDate' => 'required|string|date',
            'jobNo' => 'required|integer',
            'workOrderDate' => 'required|string|date',
            'indentNo' => 'required|string|alpha_num:ascii',
            'rigNo' => 'required|string',
            'kb' => 'nullable|numeric',
            'gl' => 'nullable|numeric',
            'unitNo' => 'required|string',
            'logging_unit_type' => 'required|in:departmental,contractual',
            'loggingType' => 'required|string',
            'logType' => 'required|string',
            'wellOwner' => 'required|string',
            'mastVanNo' => 'nullable|string',
            'lvNo' => 'required|string',
            'wellType' => 'required|string',
            'rigType' => 'required|string',

            // time information
            'assembled_date' => 'required|date',
            'assembled_time' => 'required|date_format:H:i',
            'depOffice_date' => 'required|date',
            'depOffice_time' => 'required|date_format:H:i',
            'arrivalSite_date' => 'required|date',
            'arrivalSite_time' => 'required|date_format:H:i',
            'indented_date' => 'required|date',
            'indented_time' => 'required|date_format:H:i',
            'wellReadiness_date' => 'required|date',
            'wellReadiness_time' => 'required|date_format:H:i',
            'wellTaken_date' => 'required|date',
            'wellTaken_time' => 'required|date_format:H:i',
            'rigUP_date' => 'required|date',
            'rigUP_time' => 'required|date_format:H:i',
            'wellHandOver_date' => 'required|date',
            'wellHandOver_time' => 'required|date_format:H:i',
            'depSite_date' => 'required|date',
            'depSite_time' => 'required|date_format:H:i',
            'arrivalOffice_date' => 'required|date',
            'arrivalOffice_time' => 'required|date_format:H:i',
            'preparationTime' => 'required|numeric',
            'postProceTime' => 'required|numeric',

            // well information
            'depthDriller' => 'nullable|string',
            'depthLogger' => 'nullable|string',
            'casingSize' => 'nullable|string',
            'casingShoeDriller' => 'nullable|string',
            'casingShoeLogger' => 'nullable|string',
            'floatCollar' => 'nullable|string',
            'bitSize' => 'nullable|string',
            'tubingSize' => 'nullable|string',
            't_shoe_Packer' => 'nullable|string',
            's_nippletopexp' => 'nullable|string',
            'THP' => 'nullable|string',
            'maxDevAt' => 'nullable|string',
            'distTo_FroKms' => 'nullable|string',

            // mud information,
            'rm' => 'nullable|string',
            'rmtemp' => 'nullable|string',
            'rmf' => 'nullable|string',
            'rmftemp' => 'nullable|string',
            'rmc' => 'nullable|string',
            'rmctemp' => 'nullable|string',
            'bht' => 'nullable|string',
            'bhtdepth' => 'nullable|string',
            'spgr' => 'nullable|string',
            'viscosity' => 'nullable|string',
            'mudType' => 'nullable|string',
            'waterloss' => 'nullable|string',
            'ph' => 'nullable|string',
            'oilpercnt' => 'nullable|string',
            'kcl_barytes' => 'nullable|string',
            'salinity' => 'nullable|string',
            'lastcirc_from' => 'nullable|date_format:Y-m-d H:i',
            'lastcirc_to' => 'nullable|date_format:Y-m-d H:i',

            // cable information'
            'cableSize' => 'required|string',
            'insulation' => 'required|string',
            'shoeDate' => 'required|date|string',
            'weakPoint' => 'required|string',
            'cableHeadSize' => 'required|string',
            'cableLength' => 'required|numeric',
            'initialLength' => 'required|numeric',

            // equipment status'
            'surfaceEquipment' => 'required|string',
            'automobile' => 'required|string',
            'wellCondition' => 'required|string',
            'timeLoss' => 'required|string',

            // personnel
            'personnel' => 'array',
            'personnel.*.user_id' => 'integer',

            // logs recorded
            'logrecorded' => 'array',
            'logrecorded.*.id' => 'sometimes|integer',
            'logrecorded.*.runNo' => 'integer',
            'logrecorded.*.logRecorded' => 'string',
            'logrecorded.*.bottomDepth' => 'numeric',
            'logrecorded.*.topDepth' => 'numeric',
            'logrecorded.*.toolNo' => 'nullable|string',
            'logrecorded.*.logQuality' => 'string',
            'logrecorded.*.bottomShotDepth' => 'nullable|numeric',
            'logrecorded.*.topShotDepth' => 'nullable|numeric',
            'logrecorded.*.charge' => 'nullable|string',
            'logrecorded.*.chargeNo' => 'nullable|integer',
            'logrecorded.*.primaChord' => 'nullable|string',
            'logrecorded.*.primaChordQty' => 'nullable|numeric',
            'logrecorded.*.fuse' => 'nullable|string',
            'logrecorded.*.fuseNo' => 'nullable|integer',
            'logrecorded.*.fMf' => 'nullable|string',
            'logrecorded.*.otherLogDescription' => 'nullable|string',

            // side wall core'
            'attempted' => 'nullable|integer',
            'recovered' => 'nullable|integer',
            'missFire' => 'nullable|integer',
            'barrelLost' => 'nullable|integer',
            'emptyBarrel' => 'nullable|integer',
            'chargeUsed' => 'nullable|integer',

            'explosive' => 'nullable|array',
            'explosive.*.id' => 'sometimes|integer',
            'explosive.*.explosive' => 'nullable|string',
            'explosive.*.issued' => 'nullable|numeric',
            'explosive.*.used' => 'nullable|numeric',
            'explosive.*.returned' => 'nullable|numeric',

            // hse data'
            'permitType' => 'required|string',
            'permitNo' => 'required|string',
            'permitWork' => 'required|integer',
            'elecLockout' => 'nullable|integer',
            'elecLockoutNo' => 'nullable|string',
            'safetyMeeting' => 'required|integer',
            'jobCloseMeeting' => 'required|integer',
            'nearMiss' => 'required|integer',
            'nearMissDesc' => 'nullable|string',

            // job completion
            'jobStatus' => 'required|string',
            'remarks' => 'required|string',

            // Production Loggin
            'objective' => 'nullable|string',
            'observations' => 'nullable|string',

            // contingent worker
            'contingents' => 'nullable|string',

            // final submit
            'final_submit' => 'sometimes|nullable|integer',

            // checklists being attached
            'checklist_ids' => 'nullable|array',
            'checklist_ids.*' => 'integer|exists:explosive_checklists,id',

            'time_register_id' => 'required|exists:time_registers,id',
        ];
        if ($jcr) {
            foreach ($rules as $field => $rule) {
                if (str_contains($field, '.*.')) {
                    continue; // Skip array validation rules for update
                }
                if (is_string($rule) && str_contains($rule, 'unique:')) {
                    $rules[$field] = Rule::unique('jcr', $field)->ignore($jcr->id);
                }
            }
        }

        return $request->validate($rules);
    }

    /**
     * Push JCR to SAP
     */
    public function pushToSap(Request $request, Jcr $jcr)
    {
        $user = Auth::user();

        // Check if user has technical_support_group role
        if (!$user->hasAnyRole(['Technical_Support_Group'])) {
            return back()->with('error', 'You do not have permission to push JCRs to SAP. Only Technical Support Group can perform this action.');
        }

        // Check if JCR is approved and signed
        if (!$jcr->canPushToSap()) {
            return back()->with('error', 'JCR must be approved and signed by Operation Incharge before pushing to SAP.');
        }

        // Check if already pushed
        if ($jcr->isPushedToSap()) {
            return back()->with('error', 'This JCR has already been pushed to SAP.');
        }

        try {
            // Use SAP Service to push JCR
            $sapService = new SapService();
            $result = $sapService->pushJcrToSap($jcr);

            if ($result['success']) {
                return redirect()->route('jcr.show', $jcr->id)
                    ->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to push JCR to SAP: ' . $e->getMessage());
        }
    }
}

