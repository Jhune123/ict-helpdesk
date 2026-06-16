<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Form</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; background-color: #525659; margin: 0; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .controls { margin-bottom: 20px; }
        .btn { background-color: #007bff; color: white; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; border-radius: 5px; }
        .document-page { background: white; width: 210mm; min-height: 297mm; padding: 20mm; box-sizing: border-box; box-shadow: 0 0 10px rgba(0,0,0,0.5); line-height: 1.5; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-family: Arial, sans-serif; }
        .header-table th, .header-table td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: middle; }
        .header-table th { text-align: center; font-size: 14pt; text-transform: uppercase; }
        .doc-details { font-size: 9pt; line-height: 1.3; }
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
        .form-row { margin-bottom: 6px; display: table; width: 100%; }
        .form-label { display: table-cell; white-space: nowrap; padding-right: 5px; padding-left: 15px; width: 1%; }
        .form-line { display: table-cell; border-bottom: 1px solid #000; width: 99%; vertical-align: bottom; padding-left: 5px; font-weight: bold; }
        .textarea-box { width: 100%; border-bottom: 1px solid #000; min-height: 60px; padding: 5px 0; margin-bottom: 10px; line-height: 1.6; background: repeating-linear-gradient(to bottom, transparent, transparent 1.4em, #000 1.4em, #000 1.5em); }
        .disclaimer { font-size: 10pt; margin: 15px 0 15px 15px; }
        .signature-section { margin-top: 15px; margin-left: 15px; }
        .sig-table { width: 100%; margin-top: 25px; text-align: center; table-layout: fixed; }
        .sig-table td { vertical-align: top; padding: 0 10px; }
        .sig-line { border-bottom: 1px solid #000; height: 15px; margin-bottom: 5px; }
        .ack-section { margin-top: 25px; }
        .ack-text { text-indent: 30px; margin-bottom: 30px; }
        @media print {
            @page { size: A4 portrait; margin: 0; }
            body { background-color: white; margin: 0; padding: 0; display: block; }
            .controls { display: none; }
            .document-page { box-shadow: none; width: 100%; min-height: auto; padding: 15mm; }
        }
    </style>
</head>
<body>
    <div class="controls">
        <button class="btn" onclick="window.print()">Print / Save as PDF</button>
    </div>
    <div class="document-page">
        <table class="header-table">
            <tr>
                <td style="width: 30%; text-align: center; font-size: 10pt;">
                    <strong>Kalinga State University</strong><br>
                    INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE
                </td>
                <th style="width: 40%;">Incident Report Form</th>
                <td style="width: 30%;" class="doc-details">
                    <strong>Doc. Ref No.:</strong> KSU-ICTO-QF-06<br>
                    <strong>Effectivity Date:</strong> March 24, 2026<br>
                    <strong>Revision No.:</strong> 3.0<br>
                    <strong>Page No.:</strong> 1
                </td>
            </tr>
        </table>

        <div style="display: table; width: 100%; margin-bottom: 15px;">
            <div style="display: table-cell; width: 60%; font-weight: bold;">
                Request No.: <span style="display: inline-block; width: 250px; border-bottom: 1px solid #000; font-weight: normal; padding-left: 5px;">{{ $ticket->ticket_number ?? '' }}</span>
            </div>
            <div style="display: table-cell; text-align: right; font-style: italic; font-size: 10pt; width: 40%;">
                To be filled by KSU ICT User
            </div>
        </div>

        <div class="section-title">1. EMPLOYEE INFORMATION</div>
        <div class="form-row"><div class="form-label">User’s Full Name:</div><div class="form-line">{{ $ticket->client_name ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">User’s Office Name & Position:</div><div class="form-line">{{ $ticket->department ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">User’s Contact Number:</div><div class="form-line">{{ $ticket->contact_number ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Email Address:</div><div class="form-line">{{ $ticket->form_data['email'] ?? '' }}</div></div>

        <div class="section-title">2. INCIDENT DETAILS:</div>
        <div class="form-row"><div class="form-label">Date of Incident:</div><div class="form-line">{{ $ticket->form_data['incident_date'] ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Time of Incident:</div><div class="form-line">{{ $ticket->form_data['incident_time'] ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Location of Incident:</div><div class="form-line">{{ $ticket->form_data['location'] ?? '' }}</div></div>

        <div class="section-title">3. INCIDENT DESCRIPTION:</div>
        <div class="form-row" style="margin-left: 15px; margin-bottom: 20px;">
            <div style="width: 100%; min-height: 4.5em; border-bottom: 1px solid #000; line-height: 1.5; text-decoration: underline; text-decoration-color: #000;">
                {{ $ticket->description ?? str_repeat('&nbsp;', 300) }}
            </div>
        </div>

        <div class="section-title">4. DAMAGED/STOLEN EQUIPMENT INFORMATION:</div>
        <div class="form-row"><div class="form-label">Equipment Name/Type:</div><div class="form-line">{{ $ticket->equipment_type ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Quantity:</div><div class="form-line">{{ $ticket->form_data['quantity'] ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Equipment Serial Number:</div><div class="form-line">{{ $ticket->serial_no ?? '' }}</div></div>
        <div class="form-row"><div class="form-label">Remarks:</div><div class="form-line">{{ $ticket->remarks ?? '' }}</div></div>

        <div class="section-title">5. ACTIONS TAKEN:</div>
        <div class="form-row" style="margin-left: 15px; margin-bottom: 10px;">
            <div style="width: 100%; min-height: 3em; border-bottom: 1px solid #000; line-height: 1.5; text-decoration: underline; text-decoration-color: #000;">
                {{ $ticket->form_data['actions_taken'] ?? str_repeat('&nbsp;', 200) }}
            </div>
        </div>

        <div class="disclaimer">By submitting this form, you acknowledge that the information provided is accurate and complete.</div>

        <div class="signature-section">
            <div>Prepared by:</div>
            <table class="sig-table">
                <tr>
                    <td style="width: 35%;"><div class="sig-line"></div>Employee Name</td>
                    <td style="width: 35%;"><div class="sig-line"></div>Employee Signature</td>
                    <td style="width: 30%;"><div class="sig-line"></div>Date</td>
                </tr>
            </table>
        </div>

        <div class="signature-section" style="margin-top: 15px;">
            <div>Conformed by:</div>
            <table class="sig-table">
                <tr>
                    <td style="width: 35%;"><div class="sig-line"></div>Supervisor Name</td>
                    <td style="width: 35%;"><div class="sig-line"></div>Supervisor Signature</td>
                    <td style="width: 30%;"><div class="sig-line"></div>Date</td>
                </tr>
            </table>
        </div>

        <div class="ack-section">
            <div class="section-title">USER ACKNOWLEDGEMENT</div>
            <div class="ack-text">I, the undersigned, hereby acknowledge that the ICT services requested have been completed to my satisfaction.</div>
            
            <table style="width: 100%; text-align: center; table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td style="width: 50%; padding-right: 20px;">
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Signature over printed name:</span>
                            <div style="border-bottom: 1px solid #000; flex-grow: 1; height: 20px;"></div>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 20px;">
                        <div style="display: flex; align-items: flex-end;">
                            <span style="white-space: nowrap; margin-right: 10px;">Date:</span>
                            <div style="border-bottom: 1px solid #000; flex-grow: 1; height: 20px;"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>