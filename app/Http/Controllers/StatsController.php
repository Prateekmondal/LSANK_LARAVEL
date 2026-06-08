<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Jcr;

class StatsController extends Controller
{
    /**
     * Return aggregated JCR statistics across all tenant databases.
     */
    public function aggregatedStats(Request $request)
    {
        $tenants = Tenant::all();

        $loggingTypes = [
            'Open Hole Logging' => 0,
            'Cased Hole Logging' => 0,
            'Production Logging' => 0,
        ];

        $owners = [
            'Departmental' => 0,
            'Contractual' => 0,
        ];

        foreach ($tenants as $tenant) {
            try {
                tenancy()->initialize($tenant);

                // Aggregate loggingType counts
                $typeCounts = Jcr::query()
                    ->selectRaw('COALESCE(loggingType, "") as type, COUNT(*) as count')
                    ->groupBy('loggingType')
                    ->get();

                foreach ($typeCounts as $row) {
                    $label = $row->type ?? '';
                    if (isset($loggingTypes[$label])) {
                        $loggingTypes[$label] += (int) $row->count;
                    } else {
                        $loggingTypes[$label] = ($loggingTypes[$label] ?? 0) + (int) $row->count;
                    }
                }

                // Aggregate wellOwner counts
                $ownerCounts = Jcr::query()
                    ->selectRaw('COALESCE(wellOwner, "") as owner, COUNT(*) as count')
                    ->groupBy('wellOwner')
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
                // Skip tenant on any errors (e.g., missing tables)
                continue;
            } finally {
                tenancy()->end();
            }
        }

        return response()->json([
            'loggingType' => $loggingTypes,
            'owner' => $owners,
        ]);
    }
}
