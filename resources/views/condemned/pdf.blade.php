<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Condemned Equipment Certification</title>
    <style>
        @page { margin: 0.5in; }
        body { font-family: Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #000; }
        
        /* ISO Form Header Grid */
        .iso-header { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .iso-header td { border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; }
        
        /* Logo Container Formatting */
        .logo-container { text-align: center; white-space: nowrap; padding: 4px !important; width: 24%; }
        .header-logo { height: 50px; width: auto; display: inline-block; vertical-align: middle; margin: 0 4px; }
        
        /* Metadata Internal Layout Grid */
        .meta-text-table { width: 100%; border-collapse: collapse; font-size: 11px; text-align: left; }
        .meta-text-table td { border: 1px solid #000 !important; padding: 5px 6px !important; vertical-align: middle; }
        
        .cert-title { text-align: center; font-size: 24px; font-weight: bold; margin: 35px 0 25px 0; letter-spacing: 1px; }
        .cert-body { text-align: justify; font-size: 14px; line-height: 2.2; margin-bottom: 15px; }
        
        /* Printable Underline Fields Structure */
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
            margin: 10px 0 20px 0; 
            padding: 10px 0; 
            min-height: 70px; 
            border-bottom: 1px solid #000;
            font-style: italic; 
            font-size: 14px;
            line-height: 1.8;
        }
        
        /* Document Layout Signature Block Alignment */
        .sig-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .sig-table td { vertical-align: top; padding: 8px 0; }
        .sig-line { border-bottom: 1px solid #000; text-align: center; font-weight: bold; margin-bottom: 2px; margin-top: 30px; font-size: 13px; min-height: 18px; }
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
                <strong style="font-size: 16px; display: block; margin-bottom: 2px;">Kalinga State University</strong>
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

    <div style="font-size: 12px; margin-bottom: 15px;"><strong>Certification Number:</strong> {{ $condemnedEquipment->ticket_number ?? '____________________' }}</div>

    <div class="cert-title">CERTIFICATION</div>

    <div class="cert-body" style="text-indent: 0.5in;">
        This is to certify that the 
        <span class="underline" style="min-width: 250px;">
            {{ $condemnedEquipment->equipment_type ?? '' }} 
            @if(!empty($condemnedEquipment->brand_model))
                ({{ $condemnedEquipment->brand_model }})
            @endif
        </span> 
        with serial number 
        <span class="underline" style="min-width: 180px;">{{ $condemnedEquipment->serial_no ?? '____________________' }}</span> 
        issued to the 
        <span class="underline" style="min-width: 250px;">{{ $condemnedEquipment->department ?? $condemnedEquipment->client_name }}</span> 
        has undergone a series of troubleshooting.
    </div>

    <div style="margin-top: 25px; font-size: 14px;"><strong><em>(Reason)</em></strong></div>
    <div class="reason-box">
        {{ $condemnedEquipment->description ?? '' }}
    </div>

    <div class="cert-body">
        The ICT Office hereby certifies that the device is subject to condemnation/return to the supply office.
    </div>

    <div class="cert-body" style="margin-top: 20px;">
        Issued on the 
        <span class="underline" style="min-width: 40px;">{{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('d') : '_____' }}</span> 
        day of 
        <span class="underline" style="min-width: 140px;">{{ $condemnedEquipment->date_condemned ? $condemnedEquipment->date_condemned->format('F') : '______________' }}</span> 
        @if($condemnedEquipment->date_condemned)
            , <span class="underline" style="min-width: 50px;">{{ $condemnedEquipment->date_condemned->format('Y') }}</span>
        @endif
        at the KSU ICT Office, Bulanao, Tabuk City, Kalinga.
    </div>

    <table class="sig-table">
        <tr>
            <td style="width: 45%; padding-right: 5%; padding-bottom: 25px;">
                <div>Prepared by:</div>
                <div class="sig-line">{{ $condemnedEquipment->it_personnel ?? '' }}</div>
                <div style="text-align: center; font-size: 12px;">Technical Services Head, ICTO</div>
            </td>
            <td style="width: 45%; padding-left: 5%; padding-bottom: 25px;">
                <div>Certified by:</div>
                <div class="sig-line">{{ $condemnedEquipment->certified_by ?? '' }}</div>
                <div style="text-align: center; font-size: 12px;">Director, ICTO</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 15px; font-size: 13px;">
                <strong>Received by:</strong>
            </td>
        </tr>
        <tr>
            <td style="width: 65%; padding-right: 5%;">
                <div class="sig-line" style="margin-top: 25px;">{{ $condemnedEquipment->supply_officer ?? '' }}</div>
                <div style="text-align: center; font-size: 11px;">Signature over printed name (Supply Officer)</div>
            </td>
            <td style="width: 35%;">
                <div class="sig-line" style="margin-top: 25px;">
                    {{ $condemnedEquipment->date_received_supply ? $condemnedEquipment->date_received_supply->format('m/d/Y') : '' }}
                </div>
                <div style="text-align: center; font-size: 11px;">Date</div>
            </td>
        </tr>
        <tr>
            <td style="width: 65%; padding-right: 5%; padding-top: 15px;">
                <div class="sig-line" style="margin-top: 25px;">{{ $condemnedEquipment->client_name ?? '' }}</div>
                <div style="text-align: center; font-size: 11px;">Signature over printed name (End-User)</div>
            </td>
            <td style="width: 35%; padding-top: 15px;">
                <div class="sig-line" style="margin-top: 25px;">
                    {{ $condemnedEquipment->date_received_user ? $condemnedEquipment->date_received_user->format('m/d/Y') : '' }}
                </div>
                <div style="text-align: center; font-size: 11px;">Date</div>
            </td>
        </tr>
    </table>

</body>
</html>