<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Client Satisfaction Survey Report</title>
    <style>
        /* 🌟 DejaVu Sans: Stops checkmarks and symbols from turning into '?' marks */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Official Header Table Styles */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .header-table th, .header-table td { border: 1px solid #000; padding: 6px; vertical-align: middle; }
        .header-table .logos { text-align: center; width: 25%; }
        .header-table .titles { text-align: center; width: 45%; }
        .header-table .doc-meta { width: 15%; font-size: 10px; }
        .header-table .doc-val { width: 15%; font-size: 10px; text-align: center; }
        
        .uni-name { font-size: 14px; font-weight: bold; margin-bottom: 4px; }
        .dept-name { font-size: 12px; font-weight: bold; margin-bottom: 4px; }
        .form-name { font-size: 13px; font-weight: bold; }

        /* Intro Paragraph Style */
        .intro-text { font-size: 11px; margin-bottom: 15px; text-align: justify; color: #222; line-height: 1.5; }

        /* Form Content Styles */
        .section-title { font-size: 12px; font-weight: bold; background-color: #f2f2f2; padding: 4px; margin-top: 15px; margin-bottom: 8px; border-left: 4px solid #0056b3; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .content-table, .content-table th, .content-table td { border: 1px solid #ccc; }
        .content-table th, .content-table td { padding: 6px; text-align: left; vertical-align: top; }
        .content-table th { background-color: #f9f9f9; font-weight: bold; width: 25%; }
        .content-table td { width: 25%; }
        
        /* Green colored check symbol formatting */
        .choice-box { font-weight: bold; color: #155724; font-size: 14px; margin-right: 5px; }
        .footer-date { text-align: right; font-size: 9px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="logos">
                <img src="{{ public_path('image/KSU-logo.png') }}" style="width: 50px; height: auto; margin-right: 5px;">
                <img src="{{ public_path('image/Bagong-Pilipinas.png') }}" style="width: 50px; height: auto;">
            </td>
            <td rowspan="4" class="titles">
                <div class="uni-name">Kalinga State University</div>
                <div class="dept-name">INFORMATION AND COMMUNICATIONS<br>TECHNOLOGY OFFICE</div>
                <div class="form-name">CLIENT SATISFACTION SURVEY FORM</div>
            </td>
            <td class="doc-meta">Doc. Ref No.:</td>
            <td class="doc-val">KSU-OQA-CSS-<br>QF-07</td>
        </tr>
        <tr>
            <td class="doc-meta">Effectivity Date:</td>
            <td class="doc-val">02/03/2026</td>
        </tr>
        <tr>
            <td class="doc-meta">Revision No.:</td>
            <td class="doc-val">2</td>
        </tr>
        <tr>
            <td class="doc-meta">Page No.:</td>
            <td class="doc-val">1</td>
        </tr>
    </table>

    <div class="intro-text">
        This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. 
        Your feedback on your recently concluded transaction will help this office provide a better service. 
        Personal information shared will be kept confidential and you always have the option to not answer this form.
    </div>

    <div class="section-title">I. Client Information</div>
    <table class="content-table">
        <tr>
            <th>Name (Optional):</th>
            <td>{{ $feedback->client_name ?? 'Anonymous' }}</td>
            <th>Date Submitted:</th>
            <td>{{ $feedback->created_at->format('F d, Y h:i A') }}</td>
        </tr>
        <tr>
            <th>Office/College Visited:</th>
            <td>{{ $feedback->office_visited }}</td>
            <th>Service/s Received:</th>
            <td>{{ $feedback->services_received }}</td>
        </tr>
        <tr>
            <th>Name of Staff who Assisted:</th>
            <td>{{ $feedback->staff_assisted }}</td>
            <th>Other Staff Involved (if any):</th>
            <td>{{ $feedback->other_staff ?? 'None' }}</td>
        </tr>
        <tr>
            <th>Client Type:</th>
            <td>{{ $feedback->client_type }} {{ $feedback->agency_name ? '('.$feedback->agency_name.')' : '' }}</td>
            <th>Demographics:</th>
            <td>Sex: {{ $feedback->sex }} | Age: {{ $feedback->age }}</td>
        </tr>
    </table>

    <div class="section-title">II. Citizen's Charter (CC) Responses</div> 
    <p style="font-size: 10.5px; color: #555; font-style: italic; margin-bottom: 10px;">
        The Citizen’s Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
    </p>
    
    <div style="margin-left: 10px; margin-bottom: 15px; line-height: 1.6;">
        <p><strong>CC1. Awareness of Citizen's Charter:</strong><br><span class="choice-box">&#10004;</span> {{ $cc1_choices[$feedback->cc1] ?? 'N/A' }}</p>
        <p><strong>CC2. Visibility of Citizen's Charter:</strong><br><span class="choice-box">&#10004;</span> {{ $cc2_choices[$feedback->cc2] ?? 'N/A' }}</p>
        <p><strong>CC3. Helpfulness of Citizen's Charter:</strong><br><span class="choice-box">&#10004;</span> {{ $cc3_choices[$feedback->cc3] ?? 'N/A' }}</p>
    </div>

    <div class="section-title">III. Service Quality Dimensions (SQD) Evaluation</div>
    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 75%; background-color: #f2f2f2;">Dimension Evaluation Question</th>
                <th style="width: 25%; text-align: center; background-color: #f2f2f2;">Client Rating</th>
            </tr>
        </thead>
        <tbody>
            @php
                $questions = [
                    "SQD0. I am satisfied with the service that I availed.",
                    "SQD1. I spent a reasonable amount of time for my transaction.",
                    "SQD2. The office followed the transaction’s requirements and steps based on the information provided.",
                    "SQD3. The steps (including payment) I needed to do for my transaction were easy and simple.",
                    "SQD4. I easily found information about my transaction from the office or its website.",
                    "SQD5. I paid a reasonable amount of fees for my transaction. (If service was free, mark the ‘N/A’ column)",
                    "SQD6. I feel the office was fair to everyone, or “walang palakasan”, during my transaction.",
                    "SQD7. I was treated courteously by the staff, and (if asked for help) the staff was helpful.",
                    "SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me."
                ];
            @endphp
            @foreach($questions as $index => $q)
                <tr>
                    <td style="width: 75%;">{{ $q }}</td>
                    <td style="width: 25%; text-align: center; font-weight: bold; color: #155724;">
                        {{ $sqd_choices[$feedback->{'sqd'.$index}] ?? 'N/A' }} ({{ $feedback->{'sqd'.$index} }})
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">IV. Suggestions & Comments</div>
    <div style="border: 1px solid #ccc; padding: 8px; background-color: #fafafa; min-height: 40px; font-style: italic;">
        @if($feedback->suggestions)
            "{!! nl2br(e($feedback->suggestions)) !!}"
        @else
            No suggestions provided.
        @endif
    </div>

    <div class="footer-date">
        Generated automatically by KSU ICTO Helpdesk System on {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>
</html>