<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SurveyReportController extends Controller
{
    /**
     * Display the Monthly/Yearly CSS Report Dashboard
     */
    public function index(Request $request)
    {
        // Default to current year
        $year = $request->input('year', date('Y'));
        $month = $request->input('month'); // Null evaluates as Yearly report

        // Base query for feedback
        $query = DB::table('feedbacks') // Change this if your table is named differently (e.g., 'ticket_feedbacks')
            ->select('*')
            ->whereYear('created_at', $year);

        // Apply Month Filter if selected
        if (!empty($month)) {
            $query->whereMonth('created_at', $month);
        }

        $surveys = $query->get();

        // 📊 Calculate QMS Metrics
        $totalResponses = $surveys->count();
        
        // We check for 'sqd0' first, fallback to 'rating' based on your previous blade files
        $distribution = [
            '5' => $surveys->filter(fn($s) => ($s->sqd0 ?? $s->rating) == 5)->count(), // Outstanding
            '4' => $surveys->filter(fn($s) => ($s->sqd0 ?? $s->rating) == 4)->count(), // Very Satisfactory
            '3' => $surveys->filter(fn($s) => ($s->sqd0 ?? $s->rating) == 3)->count(), // Satisfactory
            '2' => $surveys->filter(fn($s) => ($s->sqd0 ?? $s->rating) == 2)->count(), // Fair
            '1' => $surveys->filter(fn($s) => ($s->sqd0 ?? $s->rating) == 1)->count(), // Poor
        ];

        // Calculate average
        $sum = 0;
        foreach ($surveys as $survey) {
            $sum += (int) ($survey->sqd0 ?? $survey->rating ?? 0);
        }
        $averageScore = $totalResponses > 0 ? round($sum / $totalResponses, 2) : 0;

        // Pass variables to a new view (we will create this next if you haven't yet)
        return view('reports.css', compact(
            'surveys', 
            'year', 
            'month', 
            'totalResponses', 
            'distribution', 
            'averageScore'
        ));
    }

    /**
     * Export the CSS Report as an official PDF Document
     */
    public function exportPdf(Request $request)
    {
        // We will add the PDF logic here after the dashboard view is working!
        return "PDF Export functionality coming next!";
    }
}