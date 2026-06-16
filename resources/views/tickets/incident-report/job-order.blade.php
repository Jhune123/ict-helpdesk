<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Report Job Order</title>
    <style>
        /* Define strict single-page A4 print properties */
        @page { 
            size: A4 portrait; 
            margin: 12mm; 
        }
        
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 10.5pt; 
            color: #000; 
            background-color: #525659; 
            margin: 0; 
            padding: 20px; 
            display: flex; 
            flex-direction: column; 
            align-items: center;
        }
        
        .controls { 
            margin-bottom: 20px; 
        }
        
        .btn { 
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            font-size: 16px; 
            cursor: pointer; 
            border-radius: 5px; 
        }
        
        /* Layout canvas container */
        .document-page { 
            background: white; 
            width: 210mm; 
            min-height: 297mm; 
            padding: 15mm; 
            box-sizing: border-box; 
            box-shadow: 0 0 10px rgba(0,0,0,0.5); 
            line-height: 1.4; 
        }
        
        /* Re-engineered layout grid structure for the header */
        .header-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            font-family: Arial, sans-serif; 
        }
        
        .header-table td { 
            border: 1px solid #000; 
            vertical-align: middle; 
        }
        
        .logo-cell {
            width: 26%;
            text-align: center;
            padding: 4px;
            white-space: nowrap;
        }

        .logo-cell img {
            height: 55px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
            margin: 0 2px;
        }

        .title-cell {
            width: 44%;
            text-align: center;
            padding: 6px;
        }

        .title-cell .univ-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .title-cell .office-name {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .title-cell .form-name {
            font-size: 13pt;
            margin: 0;
        }

        .meta-cell {
            width: 30%;
            padding: 0 !important;
        }

        .meta-nested-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .meta-nested-table td {
            border: none !important;
            padding: 4px 6px !important;
            font-size: 9pt;
        }
        
        /* Body form structure styles */
        .section-title { 
            font-weight: bold; 
            margin-top: 12px; 
            margin-bottom: 4px; 
            text-transform: uppercase; 
            font-size: 11pt;
        }
        
        .form-row { 
            margin-bottom: 5px; 
            display: table; 
            width: 100%; 
        }
        
        .form-label { 
            display: table-cell; 
            white-space: nowrap; 
            padding-right: 5px; 
            padding-left: 15px; 
            width: 1%; 
        }
        
        .form-line { 
            display: table-cell; 
            border-bottom: 1px solid #000; 
            width: 99%; 
            vertical-align: bottom; 
            padding-left: 5px; 
            font-weight: bold; 
        }
        
        .disclaimer { 
            font-size: 9.5pt; 
            margin: 12px 0 12px 15px; 
        }
        
        .signature-section { 
            margin-top: 12px; 
            margin-left: 15px; 
        }
        
        .sig-table { 
            width: 100%; 
            margin-top: 20px; 
            text-align: center; 
            table-layout: fixed; 
        }
        
        .sig-table td { 
            vertical-align: top; 
            padding: 0 10px; 
            font-size: 10pt;
        }
        
        .sig-line { 
            border-bottom: 1px solid #000; 
            height: 15px; 
            margin-bottom: 4px; 
        }
        
        .ack-section { 
            margin-top: 20px; 
        }
        
        .ack-text { 
            text-indent: 30px; 
            margin-bottom: 20px; 
        }

        .ack-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ack-table td {
            padding: 0;
            vertical-align: bottom;
        }
        
        /* Direct output adjustments for native print drivers */
        @media print {
            @page { 
                size: A4 portrait; 
                margin: 0; 
            }
            body { 
                background-color: white; 
                margin: 0; 
                padding: 0; 
                display: block; 
            }
            .controls { 
                display: none; 
            }
            .document-page { 
                box-shadow: none; 
                width: 100%; 
                min-height: auto; 
                padding: 12mm; 
            }
        }
    </style>
</head>
<body>
    <div class="controls">
        <button class="btn" onclick="window.print()">Print Job Order PDF</button>
    </div>
    <div class="document-page">
        
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="C:\laragon\www\ict-helpdesk\public\image\KSU-logo.png" alt="KSU Logo">
                    <img src="C:\laragon\www\ict-helpdesk\public\image\Bagong-Pilipinas.png" alt="Bagong Pilipinas Logo">
                </td>
                
                <td class="title-cell">
                    <div class="univ-name">Kalinga State University</div>
                    <div class="office-name">INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE</div>
                    <div class="form-name">Incident Report Form</div>
                </td>
                
                <td class="meta-cell">
                    <table class="meta-nested-table">
                        <tr>
                            <td style="width: 45%; border-bottom: 1px solid #000 !important; border-right: 1px solid #000 !important;">Doc. Ref No.:</td>
                            <td style="width: 55%; border-bottom: 1px solid #000 !important; text-align: center !important; font-weight: bold;">KSU-ICTO-QF-06</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #000 !important; border-right: 1px solid #000 !important;">Effectivity Date:</td>
                            <td style="border-bottom: 1px solid #000 !important; text-align: center !important;">March 24, 2026</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: 1px solid #000 !important; border-right: 1px solid #000 !important;">Revision No.:</td>
                            <td style="border-bottom: 1px solid #000 !important; text-align: center !important;">3.0</td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid #000 !important;">Page No.:</td>
                            <td style="text-align: center !important;">1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
            <tr>
                <td style="font-weight: bold; width: 60%;">
                    Request No.: <span style="display: inline-block; width: 220px; border-bottom: 1px solid #000; font-weight: normal; padding-left: 5px;">{{ $ticket->ticket_number ?? '' }}</span>
                </td>
                <td style="text-align: right; font-style: italic; font-size: 10pt; width: 40%;">
                    To be filled by KSU ICT User
                </td>
            </tr>
        </table>

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
        <div class="form-row" style="margin-left: 15px; margin-bottom: 15px;">
            <div style="width: 100%; min-height: 4em; border-bottom: 1px solid #000; line-height: 1.4; text-decoration: underline; text-decoration-color: #000;">
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
            <div style="width: 100%; min-height: 2.8em; border-bottom: 1px solid #000; line-height: 1.4; text-decoration: underline; text-decoration-color: #000;">
                {{ $ticket->form_data['actions_taken'] ?? str_repeat('&nbsp;', 200) }}
            </div>
        </div>

        <div class="disclaimer">By submitting this form, you acknowledge that the information provided is accurate and complete.</div>

        <div class="signature-section">
            <div style="font-weight: bold;">Prepared by:</div>
            <table class="sig-table">
                <tr>
                    <td style="width: 35%;"><div class="sig-line"></div>Employee Name</td>
                    <td style="width: 35%;"><div class="sig-line"></div>Employee Signature</td>
                    <td style="width: 30%;"><div class="sig-line"></div>Date</td>
                </tr>
            </table>
        </div>

        <div class="signature-section" style="margin-top: 12px;">
            <div style="font-weight: bold;">Conformed by:</div>
            <table class="sig-table">
                <tr>
                    <td style="width: 35%;"><div class="sig-line"></div>Supervisor Name</td>
                    <td style="width: 35%;"><div class="sig-line"></div>Supervisor Signature</td>
                    <td style="width: 30%;"><div class="sig-line"></div>Date</td>
                </tr>
            </table>
        </div>

        <div class="ack-section">
            <div class="section-title" style="border-top: 1px dashed #000; padding-top: 10px;">USER ACKNOWLEDGEMENT</div>
            <div class="ack-text">I, the undersigned, hereby acknowledge that the ICT services requested have been completed to my satisfaction.</div>
            
            <table class="ack-table">
                <tr>
                    <td style="width: 175px; white-space: nowrap; padding-right: 5px;">Signature over printed name:</td>
                    <td style="border-bottom: 1px solid #000; height: 20px;">&nbsp;</td>
                    <td style="width: 40px; white-space: nowrap; padding-left: 20px; padding-right: 5px;">Date:</td>
                    <td style="width: 140px; border-bottom: 1px solid #000; height: 20px;">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>