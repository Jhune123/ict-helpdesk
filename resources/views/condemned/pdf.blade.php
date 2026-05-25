<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Condemned Equipment Certification</title>
    <style>
        @page { margin: 0.5in; }
        body { font-family: Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #000; }
        
        /* ISO Form Header Grid - Full Outer & Inner Grid Layout Lines */
        .iso-header { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .iso-header td { border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; }
        
        /* Combined Logo Cell Formatting Rules */
        .logo-container { text-align: center; white-space: nowrap; padding: 4px !important; width: 24%; }
        .header-logo { height: 50px; width: auto; display: inline-block; vertical-align: middle; margin: 0 4px; }
        
        /* FIXED: Restored complete internal horizontal and vertical row grid lines */
        .meta-text-table { width: 100%; border-collapse: collapse; font-size: 11px; text-align: left; }
        .meta-text-table td { border: 1px solid #000 !important; padding: 5px 6px !important; vertical-align: middle; }
        
        .cert-title { text-align: center; font-size: 22px; font-weight: bold; margin: 35px 0 25px 0; letter-spacing: 1px; }
        .cert-body { text-align: justify; font-size: 14px; line-height: 2.3; margin-bottom: 15px; }
        
        /* Stable line structure for PDF generation engine */
        .underline { 
            display: inline-block; 
            border-bottom: 1px solid #000; 
            text-align: center; 
            font-weight: bold; 
            padding: 0 4px;
            vertical-align: bottom;
            text-indent: 0;
        }
        
        .reason-box { 
            margin: 12px 0; 
            padding: 10px; 
            min-height: 60px; 
            border: 1px solid #000; 
            font-style: italic; 
            background-color: #fafafa;
            font-size: 13px;
        }
        
        .sig-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .sig-table td { width: 50%; vertical-align: top; padding: 8px 15px; }
        .sig-line { border-bottom: 1px solid #000; text-align: center; font-weight: bold; margin-bottom: 2px; margin-top: 28px; font-size: 13px; }
    </style>
</head>
<body>

    <table class="iso-header">
        <tr>
            <td class="logo-container">
                @if(!empty($ksuLogoBase64))
                    <img src="{{ $ksuLogoBase64 }}" class="header-logo" alt="KSU Logo">
                @endif
                @if(!empty($bpLogoBase64))
                    <img src="{{ $bpLogoBase64 }}" class="header-logo" alt="Bagong Pilipinas Logo">
                @endif
                @if(empty($ksuLogoBase64) && empty($bpLogoBase64))
                    <span style="font-size: 9px; font-weight: bold;">KSU / BP LOGOS</span>
                @endif
            </td>
            
            <td style="width: 46%;">
                <strong style="font-size: 15px; display: block; margin-bottom: 2px;">Kalinga State University</strong>
                <span style="font-size: 10px; display: block; margin-bottom: 2px; letter-spacing: 0.5px;">INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE</span>
                <strong style="font-size: 11px; display: block;">Condemned Equipment Certification Form</strong>
            </td>
            
            <td style="width: 30%; padding: 0;">
                <table class="meta-text-table">
                    <tr><td style="width: 40%; border-top: none !important; border-left: none !important;"><strong>Doc. Ref No.:</strong></td><td style="border-top: none !important; border-right: none !important;">KSU-ICTO-QF-09</td></tr>
                    <tr><td style="border-left: none !important;"><strong>Effectivity Date:</strong></td><td style="border-right: none !important;">March 24, 2026</td></tr>
                    <tr><td style="border-left: none !important;"><strong>Revision No.:</strong></td><td style="border-right: none !important;">3.0</td></tr>
                    <tr><td style="border-bottom: none !important; border-left: none !important;"><strong>Page No.:</strong></td><td style="border-bottom: none !important; border-right: none !important;">1</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="font-size: 11px; margin-bottom: 10px;"><strong>Certification Number:</strong> {{ $condemnedEquipment->ticket_number }}</div>

    <div class="cert-title">CERTIFICATION</div>

    <div class="cert-body" style="text-indent: 0.4in;">
        This is to certify that the 
        <span class="underline" style="min-width: 200px;">
            {{ $condemnedEquipment->equipment_type }} 
            @if(!empty($condemnedEquipment->brand_model))
                ({{ $condemnedEquipment->brand_model }})
            @endif
        </span> 
        with serial number 
        <span class="underline" style="min-width: 150px;">{{ $condemnedEquipment->serial_no ?? 'N/A' }}</span> 
        issued to the 
        <span class="underline" style="min-width: 260px;">{{ $condemnedEquipment->department ?? 'N/A' }} / {{ $condemnedEquipment->client_name }}</span> 
        has undergone a series of systematic technical troubleshooting evaluations.
    </div>

    <div style="margin-top: 15px;"><strong><em>(Reason for Condemned Status)</em></strong></div>
    <div class="reason-box">{{ $condemnedEquipment->description ?? 'No explicit diagnostic notes cataloged.' }}</div>

    <div class="cert-body" style="margin-top: 15px;">
        The Information and Communications Technology Office hereby certifies that the device listed above is completely unserviceable and subject to condemnation/return to the property and supply office for proper disposal.
    </div>

    <div class="cert-body" style="margin-top: 15px;">
        Issued on the 
        <span class="underline" style="min-width: 35px;">{{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('d') : '___' }}</span> 
        day of 
        <span class="underline" style="min-width: 100px;">{{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('F') : '___________' }}</span>, 
        {{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('Y') : now()->format('Y') }}
        at the KSU ICT Office, Bulanao, Tabuk City, Kalinga.
    </div>

    <table class="sig-table">
        <tr>
            <td>
                <div>Prepared by:</div>
                <div class="sig-line">{{ $condemnedEquipment->it_personnel ?? 'ICT Support Staff' }}</div>
                <div style="text-align: center; font-size: 11px; color: #333;">Technical Services Head, ICTO</div>
            </td>
            <td>
                <div>Certified by:</div>
                <div class="sig-line">{{ $condemnedEquipment->certified_by ?? '__________________________________' }}</div>
                <div style="text-align: center; font-size: 11px; color: #333;">Director, ICTO</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;">
                <div>Received by:</div>
                <div class="sig-line">{{ $condemnedEquipment->supply_officer ?? '__________________________________' }}</div>
                <div style="text-align: center; font-size: 11px; color: #333;">Signature over printed name (Supply Officer)</div>
            </td>
            <td style="padding-top: 20px;">
                <div style="margin-top: 28px; border-bottom: 1px solid #000; text-align: center; font-weight: bold;">
                    {{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('m/d/Y') : '&nbsp;' }}
                </div>
                <div style="text-align: center; font-size: 11px; margin-top: 2px; color: #333;">Date</div>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 20px;">
                <div>Acknowledged by End-User:</div>
                <div class="sig-line">{{ $condemnedEquipment->client_name }}</div>
                <div style="text-align: center; font-size: 11px; color: #333;">Signature over printed name (End-User)</div>
            </td>
            <td style="padding-top: 20px;">
                <div style="margin-top: 28px; border-bottom: 1px solid #000; text-align: center;">&nbsp;</div>
                <div style="text-align: center; font-size: 11px; margin-top: 2px; color: #333;">Date</div>
            </td>
        </tr>
    </table>

</body>
</html>