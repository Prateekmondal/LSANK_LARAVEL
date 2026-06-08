<?php

namespace App\Http\Controllers;

use App\Models\Jcr;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxCallController extends Controller
{
    //
    public function getUsers()
    {
        $user = User::orderBy('seniority')->get()->where('tenant_id',Auth::user()->tenant_id)->where('status', 1);
        return response()->json($user);
    }
    public function getCableinfo(Request $request)
    {
        $cableinfo = Jcr::where('unitNo', '=', $request['unitNo'])->where('cableSize', '=', $request['cableSize'])->get(['shoeDate', 'weakPoint', 'cableLength', 'initialLength'])->last();
        return response()->json($cableinfo);
    }

    public function getJobNo(Request $request)
    {
        $jobs = Jcr::where('unitNo', '=', $request['unitNo'])->get('jobNo')->last();
        if ($jobs)
        {
            $jobNo = $jobs->jobNo + 1;
        }
        else
        {
            $jobNo = 1;
        }
        return response()->json($jobNo);
    }
}
