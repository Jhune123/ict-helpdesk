<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSM Summary Report</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        @page { size: A4 portrait; margin: 8mm; }
        body { margin: 0; padding: 20px; background: #525659; display: flex; flex-direction: column; align-items: center; }

        /* Button Container Styles */
        .btn-container { display: flex; gap: 12px; margin-bottom: 20px; }
        .action-btn {
            color: white; border: none; padding: 10px 20px; 
            font-size: 14px; font-weight: bold; border-radius: 5px; cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 6px;
            transition: background 0.2s ease;
        }
        .print-btn { background-color: #4f46e5; }
        .print-btn:hover { background-color: #4338ca; }
        .pdf-btn { background-color: #10b981; }
        .pdf-btn:hover { background-color: #059669; }

        .a4-canvas {
            width: 210mm; height: 297mm;
            background: white; padding: 8mm 10mm;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            display: flex; flex-direction: column;
            justify-content: space-between;
        }

        /* 1. HEADER SECTION */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; border: 1px solid #000; }
        .header-table td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        .title-txt { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .sub-txt { font-size: 9pt; font-weight: bold; }
        .meta-txt { font-size: 8pt; text-align: left; padding-left: 10px !important; }

        /* 2. EXECUTIVE HIGHLIGHTS GRID */
        .section-title { font-weight: bold; font-size: 9.5pt; background: #f0f0f0; padding: 3px; border: 1px solid #000; margin-bottom: 6px; }
        .highlights-grid { display: flex; justify-content: space-between; gap: 8px; margin-bottom: 8px; }
        .highlight-box { flex: 1; border: 1px solid #000; padding: 5px; border-radius: 4px; background: #fafafa; }
        .hl-title { font-size: 8pt; color: #111; text-transform: uppercase; font-weight: bold; border-bottom: 1px solid #aaa; padding-bottom: 2px; margin-bottom: 4px; text-align: center;}
        
        .mini-data-table { width: 100%; font-size: 7.5pt; text-align: left; border-collapse: collapse; }
        .mini-data-table td { padding: 1px 0; }
        .mini-data-table td:last-child { text-align: right; font-weight: bold; }

        /* 3. DATA TABLES */
        .full-table { width: 100%; border-collapse: collapse; font-size: 7.2pt; border: 1px solid #000; margin-bottom: 8px; }
        .full-table th, .full-table td { padding: 4px 3px; border: 1px solid #aaa; }
        .full-table th { background: #e8e8e8; text-align: center; font-weight: bold; }
        .full-table .text-left { text-align: left; }
        .full-table .num-col { text-align: center; font-weight: bold; }
        .cc-group-header { background: #f4f4f4; font-weight: bold; font-size: 8pt; text-align: left !important; }

        /* SQD Specific Layout Adjustments */
        .sqd-table th { vertical-align: bottom; padding-bottom: 4px; }
        .emoji-icon { display: block; font-size: 14pt; margin-bottom: 2px; }
        .metric-header { background: #dfdfdf !important; color: #000; }

        /* 4. FEEDBACK & CAPA BACKGROUNDS */
        .feedback-box { border: 1px solid #000; padding: 6px; margin-bottom: 6px; min-height: 40px; }
        .feedback-box ol { margin: 0; padding-left: 18px; font-size: 8.5pt; line-height: 1.3; }
        .capa-box { border: 1px solid #000; padding: 6px; min-height: 40px; margin-bottom: 12px; font-size: 8.5pt; color: #444; }
        
        /* 5. SIGN-OFF LINES */
        .signatures { display: flex; justify-content: space-between; padding: 0 15px; }
        .sig-block { width: 42%; text-align: center; font-size: 9pt; }
        .sig-line { border-bottom: 1px solid #000; height: 22px; margin-bottom: 4px; }

        @media print {
            body { background: white; padding: 0; display: block; }
            .btn-container { display: none; }
            .a4-canvas { box-shadow: none; width: 100%; height: 100%; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="btn-container">
        <button onclick="window.print()" class="action-btn print-btn">🖨️ Print Report</button>
        <button onclick="downloadPDF()" class="action-btn pdf-btn">📄 Save as PDF</button>
    </div>

    <div class="a4-canvas" id="report-canvas">
        
        <table class="header-table">
            <tr>
                <td style="width: 25%; text-align: center;">
                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                        <img src="{{ asset('image/KSU-logo.png') }}" alt="KSU Logo" style="max-width: 70px; height: auto;">
                        <img src="{{ asset('image/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas Logo" style="max-width: 70px; height: auto;">
                    </div>
                </td>
                
                <td style="width: 50%;">
                    <div class="title-txt">Kalinga State University</div>
                    <div class="sub-txt">ICTO - CLIENT SATISFACTION MEASUREMENT (CSM) SUMMARY REPORT</div>
                    <div style="margin-top: 2px; font-size: 9pt;">Period: <strong>{{ $month ? date('F', mktime(0, 0, 0, $month, 1)) : 'Entire Year' }} {{ $year }}</strong></div>
                </td>
                
                <td class="meta-txt" style="width: 25%; vertical-align: middle;">
                    <strong>Doc Ref:</strong> KSU-OQA-CSS-QF-07<br>
                    <strong>Date:</strong> {{ now()->format('M d, Y') }}
                </td>
            </tr>
        </table>

        <div class="section-title">I. EXECUTIVE SUMMARY & DEMOGRAPHICS</div>
        <div class="highlights-grid">
            <div class="highlight-box">
                <div class="hl-title">Core Metrics</div>
                <table class="mini-data-table" style="margin-top: 8px; font-size: 8.5pt;">
                    <tr><td>Total Respondents</td><td>{{ $surveys->count() }}</td></tr>
                    <tr><td>Overall CSAT Score</td><td style="color:#000; font-weight:bold;">
                        {{ $surveys->count() > 0 ? number_format(($surveys->avg(function($s) { return $s->sqd0 ?? $s->rating; }) / 5) * 100, 1) : 0 }}%
                    </td></tr>
                </table>
            </div>
            <div class="highlight-box">
                <div class="hl-title">Client Types</div>
                <table class="mini-data-table">
                    <tr><td>Citizen</td><td>{{ $surveys->where('client_type', 'Citizen')->count() }}</td></tr>
                    <tr><td>Business</td><td>{{ $surveys->where('client_type', 'Business')->count() }}</td></tr>
                    <tr><td>Government</td><td>{{ $surveys->where('client_type', 'Government')->count() }}</td></tr>
                </table>
            </div>
            <div class="highlight-box">
                <div class="hl-title">Sex Breakdown</div>
                <table class="mini-data-table">
                    <tr><td>Male</td><td>{{ $surveys->where('sex', 'Male')->count() }}</td></tr>
                    <tr><td>Female</td><td>{{ $surveys->where('sex', 'Female')->count() }}</td></tr>
                    <tr><td>Did Not Specify</td><td>{{ $surveys->whereNull('sex')->count() + $surveys->where('sex', '')->count() }}</td></tr>
                </table>
            </div>
            <div class="highlight-box">
                <div class="hl-title">Age Distribution</div>
                <table class="mini-data-table">
                    <tr><td>19 or below</td><td>{{ $surveys->where('age', '<=', 19)->count() }}</td></tr>
                    <tr><td>20–34 years</td><td>{{ $surveys->where('age', '>=', 20)->where('age', '<=', 34)->count() }}</td></tr>
                    <tr><td>35–49 years</td><td>{{ $surveys->where('age', '>=', 35)->where('age', '<=', 49)->count() }}</td></tr>
                    <tr><td>50–64 years</td><td>{{ $surveys->where('age', '>=', 50)->where('age', '<=', 64)->count() }}</td></tr>
                    <tr><td>65 or above</td><td>{{ $surveys->where('age', '>=', 65)->count() }}</td></tr>
                    <tr><td>Did not specify</td><td>{{ $surveys->whereNull('age')->count() + $surveys->where('age', '')->count() }}</td></tr>
                </table>
            </div>
        </div>

        <div class="section-title">II. CITIZEN'S CHARTER (CC) RESULTS</div>
        <table class="full-table">
            <tr>
                <td colspan="2" class="cc-group-header">CC1: Awareness of CC</td>
                <td colspan="2" class="cc-group-header">CC2: Visibility of CC</td>
                <td colspan="2" class="cc-group-header">CC3: Helpfulness of CC</td>
            </tr>
            <tr>
                <td>1. Knows CC & saw this office's CC</td><td class="num-col" style="width:40px;">{{ $surveys->where('cc1', 1)->count() }}</td>
                <td>1. Easy to see</td><td class="num-col" style="width:40px;">{{ $surveys->where('cc2', 1)->count() }}</td>
                <td>1. Helped very much</td><td class="num-col" style="width:40px;">{{ $surveys->where('cc3', 1)->count() }}</td>
            </tr>
            <tr>
                <td>2. Knows CC but did NOT see it</td><td class="num-col">{{ $surveys->where('cc1', 2)->count() }}</td>
                <td>2. Somewhat easy to see</td><td class="num-col">{{ $surveys->where('cc2', 2)->count() }}</td>
                <td>2. Somewhat helped</td><td class="num-col">{{ $surveys->where('cc3', 2)->count() }}</td>
            </tr>
            <tr>
                <td>3. Learned of CC only when seen</td><td class="num-col">{{ $surveys->where('cc1', 3)->count() }}</td>
                <td>3. Difficult to see</td><td class="num-col">{{ $surveys->where('cc2', 3)->count() }}</td>
                <td>3. Did not help</td><td class="num-col">{{ $surveys->where('cc3', 3)->count() }}</td>
            </tr>
            <tr>
                <td>4. Does not know CC / didn't see</td><td class="num-col">{{ $surveys->where('cc1', 4)->count() }}</td>
                <td>4. Not visible at all / N/A</td><td class="num-col">{{ $surveys->whereIn('cc2', [4, 5])->count() }}</td>
                <td>4. N/A</td><td class="num-col">{{ $surveys->where('cc3', 4)->count() }}</td>
            </tr>
        </table>

        <div class="section-title">III. SERVICE QUALITY DIMENSIONS (SQD)</div>
        <table class="full-table sqd-table">
            <thead>
                <tr>
                    <th class="text-left" style="width: 36%;">Dimension Indicator</th>
                    <th style="width: 8%;"><span class="emoji-icon">😁</span>Strongly<br>Agree</th>
                    <th style="width: 8%;"><span class="emoji-icon">🙂</span>Agree</th>
                    <th style="width: 8%;"><span class="emoji-icon">😐</span>Neither<br>Disagree</th>
                    <th style="width: 8%;"><span class="emoji-icon">☹️</span>Disagree</th>
                    <th style="width: 8%;"><span class="emoji-icon">😠</span>Strongly<br>Disagree</th>
                    <th style="width: 6%;">N/A</th>
                    <th class="metric-header" style="width: 9%;">Total<br>Answers</th>
                    <th class="metric-header" style="width: 9%;">Average<br>Score</th>
                </tr>
            </thead>
            <tbody>
                @php
                $labels = [
                    'sqd0' => '<strong>SQD0.</strong> I am satisfied with the service that I availed.',
                    'sqd1' => '<strong>SQD1.</strong> I spent a reasonable amount of time for my transaction.',
                    'sqd2' => '<strong>SQD2.</strong> The office followed the transaction’s requirements and steps based on the information provided.',
                    'sqd3' => '<strong>SQD3.</strong> The steps (including payment) I needed to do for my transaction were easy and simple.',
                    'sqd4' => '<strong>SQD4.</strong> I easily found information about my transaction from the office or its website.',
                    'sqd5' => '<strong>SQD5.</strong> I paid a reasonable amount of fees for my transaction. (If free, mark \'N/A\')',
                    'sqd6' => '<strong>SQD6.</strong> I feel the office was fair to everyone, or “walang palakasan”, during my transaction.',
                    'sqd7' => '<strong>SQD7.</strong> I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                    'sqd8' => '<strong>SQD8.</strong> I got what I needed from the government office, or (if denied) denial of request was sufficiently explained.'
                ];
                @endphp
                
                @foreach($labels as $key => $text)
                <tr>
                    <td class="text-left">{!! $text !!}</td>
                    <td class="num-col">{{ $surveys->where($key, 5)->count() }}</td>
                    <td class="num-col">{{ $surveys->where($key, 4)->count() }}</td>
                    <td class="num-col">{{ $surveys->where($key, 3)->count() }}</td>
                    <td class="num-col">{{ $surveys->where($key, 2)->count() }}</td>
                    <td class="num-col">{{ $surveys->where($key, 1)->count() }}</td>
                    <td class="num-col" style="color: #666;">{{ $surveys->whereNotIn($key, [1,2,3,4,5])->whereNotNull($key)->count() }}</td>
                    <td class="num-col metric-header">{{ $surveys->whereNotNull($key)->count() }}</td>
                    <td class="num-col metric-header" style="color: #000;">
                        {{ $surveys->whereIn($key, [1,2,3,4,5])->count() > 0 ? number_format($surveys->whereIn($key, [1,2,3,4,5])->avg($key), 1) : '0.0' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-title">IV. QUALITATIVE FEEDBACK (TOP 3 RECENT COMMENTS)</div>
        <div class="feedback-box">
            <ol>
                @forelse($surveys->whereNotNull('comments')->where('comments', '!=', '')->take(3) as $feedback)
                    <li>{{ $feedback->comments }}</li>
                @empty
                    <li style="color: #666; font-style: italic;">No specific comments recorded for this period.</li>
                @endforelse
            </ol>
        </div>

        <div class="section-title">V. CORRECTIVE & PREVENTIVE ACTION PLAN (CAPA)</div>
        <div class="capa-box">
            <strong>Action Tracking:</strong> System automatically monitors SQD scores. Any score below 3.0 will flag the requirement for immediate preventive action regarding staff behavior or facility improvements.
        </div>

        <div class="signatures">
            <div class="sig-block">
                <div style="text-align: left; font-weight: bold; font-size:8pt;">Prepared by:</div>
                <div class="sig-line"></div>
                System Administrator, ICTO
            </div>
            <div class="sig-block">
                <div style="text-align: left; font-weight: bold; font-size:8pt;">Noted by:</div>
                <div class="sig-line"></div>
                Director / Management Rep, ICTO
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('report-canvas');
            
            // Configuration options matching A4 setup precisely
            const opt = {
                margin:       0,
                filename:     'CSM_Summary_Report_{{ $month ?? "Year" }}_{{ $year }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Execute PDF capture generation and trigger down-stream client download
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>