<?php

namespace App\Exports;

use App\Models\Jcr;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JcrsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $userId;
    protected $month;

    public function __construct($userId, $month = null)
    {
        $this->userId = $userId;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Jcr::where('creator_id', $this->userId)
            ->with('logs')
            ->orderBy('arrivalOffice_date', 'desc')
            ->orderBy('arrivalOffice_time', 'desc');

        if ($this->month) {
            try {
                $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth()->toDateString();
                $end = Carbon::createFromFormat('Y-m', $this->month)->endOfMonth()->toDateString();
                $query->whereBetween('arrivalOffice_date', [$start, $end]);
            } catch (\Exception $e) {}
        }

        return $query->get()->map(function ($jcr) {
            $logs = $jcr->logs->map(function ($log) {
                $depth = $log->topShotDepth ? ($log->topShotDepth . '-' . $log->bottomShotDepth) : ($log->topDepth . '-' . $log->bottomDepth);
                return "{$log->logRecorded} ({$depth})";
            })->join('; ');

            return collect([
                'Date' => optional($jcr->arrivalOffice_date)->format('Y-m-d') ?: $jcr->jobDate,
                'Well No' => $jcr->wellNo,
                'Job No' => $jcr->jobNo,
                'Logs' => $logs,
                'Status' => ucfirst(str_replace('_', ' ', $jcr->status)),
            ]);
        });
    }

    public function headings(): array
    {
        return ['Date', 'Well No', 'Job No', 'Logs (Type & Depths)', 'Status'];
    }
}