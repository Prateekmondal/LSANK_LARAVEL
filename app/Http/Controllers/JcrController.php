<?php

namespace App\Http\Controllers;

use App\Models\Jcr;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class JcrController extends Controller
{
    //
    public function index()
    {
        $users = User::orderBy('seniority')->get()->where('status', 1);
        // dd($users);
        return view("jcr", compact('users'));
    }
    public function view()
    {
        $user = Auth::user();
        if ($user->can('view_any_jcr')) {
            $jcrs = Jcr::with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')->paginate(50);
        } else {
            # code...
            $jcrs = $user->jcrs()->with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
                ->orderBy('arrivalOffice_time', 'desc')->paginate(50);
        }
        // dd($jcrs);
        return view("viewjcr", ['jcrs' => $jcrs]);
    }
    public function dashboardView()
    {
        $user = Auth::user();
        $ch_count = $user->jcrs()->where('logType', 'CH')->count();
        $oh_count = $user->jcrs()->where('logType', 'OH')->count();
        $pl_count = $user->jcrs()->where('logType', 'PL')->count();
        $total_count = $user->jcrs()->count();
        $jcrs = $user->jcrs()->with(['users', 'logs', 'explosives'])->orderBy('arrivalOffice_date', 'desc')
            ->orderBy('arrivalOffice_time', 'desc')->paginate(10);
        // dd($jcrs);
        return view("dashboard", ['jcrs' => $jcrs, 'user' => $user, 'ch' => $ch_count, 'oh' => $oh_count, 'pl' => $pl_count, 'total' => $total_count]);
    }
    public function add(REQUEST $request): RedirectResponse
    {
        $validatedData = $this->validateRequest($request);
        $now = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
        $current_user = Auth()->user()->name;
        $validatedData['created_by'] = $current_user;
        $validatedData['created_at'] = $now;
        $validatedData['last_edited_by'] = $current_user;
        $validatedData['last_edited_at'] = $now;
        // dd($validatedData);
        try {
            DB::beginTransaction();
            $jcr = Jcr::create(Arr::except($validatedData, ['personnel', 'logrecorded', 'explosive']));

            foreach ($validatedData['personnel'] as $personnel) {
                $jcr->users()->attach($personnel['user_id']);
            }
            foreach ($validatedData['logrecorded'] as $logrecorded) {
                $jcr->logs()->create($logrecorded);
            }
            foreach ($validatedData['explosive'] as $explosive) {
                if (!is_null($explosive['explosive'])) {
                    $jcr->explosives()->create($explosive);
                }
            }
            DB::commit();
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return back()->withInput()->with('error', 'Failed to create JCR: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jcr  $jcr
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request): RedirectResponse|View
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jcr  $jcr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): RedirectResponse
    {
        $validatedData = $this->validateRequest($request);
        $now = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
        $current_user = Auth()->user()->name;
        $validatedData['last_edited_by'] = $current_user;
        $validatedData['last_edited_at'] = $now;
        $validatedData['final_submitted_by'] = $current_user;
        $validatedData['final_submitted_at'] = $now;
        // dd($validatedData);
        try {
            DB::beginTransaction();
            $jcr = Jcr::find($validatedData['id']);
            $jcr->update(Arr::except($validatedData, ['id', 'personnel', 'logrecorded', 'explosive']));

            // Sync users if provided
            if ($request->has('personnel')) {
                $personnelIds = collect($request->input('personnel.*.user_id'))->flatten()->unique()->toArray();
                $jcr->users()->sync($personnelIds);
            }

            // Update or create associated logs if provided
            if ($request->has('logrecorded')) {
                $this->syncLogs($jcr, $validatedData['logrecorded']);
            }

            // Update or create associated explosives if provided
            if ($request->has('explosive')) {
                // dd($jcr->explosives());
                $this->syncExplosives($jcr, $validatedData['explosive']);
            }
            DB::commit();
            return redirect()->route('jcr.view')->with('success', 'JCR updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update JCR: ' . $e->getMessage());
        }
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

    /**
     * Validate the request data.
     */
    protected function validateRequest(Request $request, Jcr $jcr = null)
    {
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
            'final_submitted' => 'sometimes|nullable|integer',

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
}
