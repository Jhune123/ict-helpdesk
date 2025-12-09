<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Order - <?php echo e($ticket->ticket_number); ?></title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #003366;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            display: block;
            margin: 0 auto 10px auto;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #003366;
        }
        .header h4 {
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            background: #003366;
            color: white;
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        td, th {
            border: 1px solid #444;
            padding: 6px;
            vertical-align: top;
        }
        .value {
            font-weight: bold;
        }
        .signature-box {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box td {
            border: none;
            text-align: center;
            padding-top: 40px;
        }
        .label {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <?php
        $path = public_path('image/KSU-logo.png'); // your logo location
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>

    <div class="header">
        <img src="<?php echo e($base64); ?>" alt="KSU Logo">
        <h2>KALINGA STATE UNIVERSITY</h2>
        <h4>ICTO - HELP DESK MANAGEMENT SYSTEM</h4>
        <h4><strong>JOB ORDER FORM</strong></h4>
    </div>

    <!-- JOB ORDER INFO -->
    <table>
        <tr>
            <td><strong>Job Order No.:</strong></td>
            <td class="value"><?php echo e($ticket->ticket_number); ?></td>
            <td><strong>Date Submitted:</strong></td>
            <td class="value"><?php echo e($ticket->date_submitted ? $ticket->date_submitted->format('F d, Y') : '—'); ?></td>
        </tr>

        <tr>
            <td><strong>Status:</strong></td>
            <td class="value"><?php echo e($ticket->status); ?></td>
            <td><strong>Date Finished:</strong></td>
            <td class="value">
                <?php echo e($ticket->date_finished ? $ticket->date_finished->format('F d, Y') : '—'); ?>

            </td>
        </tr>
    </table>

    <!-- CLIENT INFO -->
    <div class="section-title">CLIENT INFORMATION</div>
    <table>
        <tr>
            <td width="25%"><strong>Client Name</strong></td>
            <td class="value"><?php echo e($ticket->client_name); ?></td>
        </tr>
        <tr>
            <td><strong>Department</strong></td>
            <td class="value"><?php echo e($ticket->department); ?></td>
        </tr>
        <tr>
            <td><strong>Contact Number</strong></td>
            <td class="value"><?php echo e($ticket->contact_number ?? '—'); ?></td>
        </tr>
    </table>

    <!-- ISSUE DETAILS -->
    <div class="section-title">ISSUE / SERVICE REQUESTED</div>
    <table>
        <tr>
            <td><strong>Category</strong></td>
            <td class="value"><?php echo e($ticket->category->name ?? '—'); ?></td>
        </tr>
        <tr>
            <td><strong>Priority</strong></td>
            <td class="value"><?php echo e($ticket->priority); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Description:</strong><br>
                <?php echo e($ticket->description); ?>

            </td>
        </tr>
        <?php if($ticket->remarks): ?>
        <tr>
            <td colspan="2">
                <strong>Technician Notes / Remarks:</strong><br>
                <?php echo e($ticket->remarks); ?>

            </td>
        </tr>
        <?php endif; ?>
    </table>

    <!-- ASSIGNEE -->
    <div class="section-title">ASSIGNED PERSONNEL</div>
    <table>
        <tr>
            <td width="25%"><strong>Assigned To (IT Staff)</strong></td>
            <td class="value"><?php echo e($ticket->assignee->name ?? 'Not Assigned'); ?></td>
        </tr>
    </table>

    <!-- SIGNATURE SECTION -->
    <table class="signature-box">
        <tr>
            <td>
                ________________________________ <br>
                <span class="label">IT Personnel Signature</span>
            </td>
            <td>
                ________________________________ <br>
                <span class="label">Client Signature</span>
            </td>
        </tr>
    </table>

</body>
</html>
<?php /**PATH C:\laragon\www\ict_helpdesk\resources\views/tickets/job_order.blade.php ENDPATH**/ ?>