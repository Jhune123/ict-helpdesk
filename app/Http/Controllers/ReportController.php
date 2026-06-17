<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClientSurvey; // Assuming this is your model name

class ReportController extends Controller
{
    public function generateCsmReport(Request $request)
    {
        // 1. Capture the dropdown filters
        $year = $request->input('year', date('Y'));
        $month = $request->input('month'); // If null, it calculates the entire year

        // 2. Base Query filtered by Date
        $query = ClientSurvey::whereYear('created_at', $year);
        if ($month && $month !== 'all') {
            $query->whereMonth('created_at', $month);
        }

        $surveys = $query->get();
        $totalRespondents = $surveys->count();

        // 3. Demographics Aggegrations
        $clientTypes = $surveys->groupBy('client_type')->map->count();
        $sexBreakdown = $surveys->groupBy('sex')->map->count();

        // Age brackets categorization logic
        $ageDistribution = [
            '19_below' => $surveys->where('age', '<=', 19)->count(),
            '20_34'    => $surveys->whereBetween('age', [20, 34])->count(),
            '35_49'    => $surveys->whereBetween('age', [35, 49])->count(),
            '50_64'    => $surveys->whereBetween('age', [50, 64])->count(),
            '65_above' => $surveys->where('age', '>=', 65)->count(),
            'unspecified' => $surveys->whereNull('age')->count(),
        ];

        // 4. CC Questionnaire Counts
        $cc1Counts = $surveys->groupBy('cc1')->map->count();
        $cc2Counts = $surveys->groupBy('cc2')->map->count();
        $cc3Counts = $surveys->groupBy('cc3')->map->count();

        // 5. Calculate SQD Averages (Excluding N/A values)
        $sqdAverages = [];
        for ($i = 0; $i <= 8; $i++) {
            $column = 'sqd' . $i;
            $sqdAverages[$column] = $surveys->whereNotNull($column)->avg($column) ?? 0;
        }

        // 6. Send everything to the A4 dynamic report page
        return view('reports.csm-summary', compact(
            'totalRespondents', 'clientTypes', 'sexBreakdown', 
            'ageDistribution', 'cc1Counts', 'cc2Counts', 'cc3Counts', 
            'sqdAverages', 'year', 'month'
        ));
    }
}