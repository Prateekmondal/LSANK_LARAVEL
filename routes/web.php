<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatsController;

// routes/web.php, api.php or any other central route files you have

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            $tenants = \App\Models\Tenant::with('domains')->get();

            // Aggregate stats across tenants to pass to the welcome view
            $loggingTypes = [
                'OH' => 0,
                'CH' => 0,
                'PL' => 0,
            ];

            $owners = [
                'departmental' => 0,
                'contractual' => 0,
            ];

            foreach ($tenants as $tenant) {
                try {
                    tenancy()->initialize($tenant);

                    // limit to current financial year for central aggregation as well
                    $now = \Carbon\Carbon::now();
                    if ($now->month >= 4) {
                        $fyStart = \Carbon\Carbon::create($now->year, 4, 1)->toDateString();
                    } else {
                        $fyStart = \Carbon\Carbon::create($now->year - 1, 4, 1)->toDateString();
                    }
                    $fyEnd = \Carbon\Carbon::parse($fyStart)->copy()->addYear()->subDay()->toDateString();

                    $typeCounts = \App\Models\Jcr::query()
                        ->whereBetween('arrivalOffice_date', [$fyStart, $fyEnd])
                        ->selectRaw('COALESCE(logType, "") as type, COUNT(*) as count')
                        ->groupBy('logType')
                        ->get();

                    foreach ($typeCounts as $row) {
                        $label = $row->type ?? '';
                        if (isset($loggingTypes[$label])) {
                            $loggingTypes[$label] += (int) $row->count;
                        } else {
                            $loggingTypes[$label] = ($loggingTypes[$label] ?? 0) + (int) $row->count;
                        }
                    }


                    $ownerCounts = \App\Models\Jcr::query()
                        ->whereBetween('arrivalOffice_date', [$fyStart, $fyEnd])
                        ->selectRaw('COALESCE(logging_unit_type, "") as owner, COUNT(*) as count')
                        ->groupBy('logging_unit_type')
                        ->get();

                    foreach ($ownerCounts as $row) {
                        $label = $row->owner ?? '';
                        if (isset($owners[$label])) {
                            $owners[$label] += (int) $row->count;
                        } else {
                            $owners[$label] = ($owners[$label] ?? 0) + (int) $row->count;
                        }
                    }

                } catch (\Exception $e) {
                    // skip tenant on error
                    continue;
                } finally {
                    tenancy()->end();
                }
            }

            $aggregatedStats = [
                'loggingType' => $loggingTypes,
                'owner' => $owners,
            ];

            return view('welcome', compact('tenants', 'aggregatedStats'));
        })->name('home');

        // Endpoint to return aggregated stats across all tenant databases
        Route::get('/tenant-stats', [StatsController::class, 'aggregatedStats'])->name('tenant.stats');
    });
}

if (app()->environment('local')) {
    // Preview error pages locally: visit /_errors/404, /_errors/500 etc.
    Route::get('/_errors/{code}', function ($code) {
        abort(intval($code));
    });
}

require __DIR__ . '/auth.php';