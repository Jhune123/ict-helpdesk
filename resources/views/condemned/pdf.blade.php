<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Condemned Equipment Report</title>
    <style>
        @page { margin: 10px; }
        body { font-family: sans-serif; margin: 0; padding: 0; }
        
        /* Font size 8px to handle 17 columns in Landscape */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 8px; 
            table-layout: fixed; 
        }
        
        th, td { 
            border: 1px solid #000; 
            padding: 2px; 
            text-align: left; 
            vertical-align: top; 
            word-wrap: break-word; 
            overflow-wrap: break-word; 
        }
        
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        
        h2 { 
            text-align: center; 
            margin-bottom: 10px; 
            font-size: 14px; 
        }
    </style>
</head>
<body>
    <h2>Condemned Equipment Report</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 7%;">Ticket #</th>
                <th>Property No</th>
                <th>Item Name</th>
                <th>Title</th>
                <th>Description</th>
                <th>Type</th>
                <th>Brand/Model</th>
                <th>Serial No</th>
                <th>Category</th>
                <th>Dept</th>
                <th>IT Person</th>
                <th>Client</th>
                <th>Priority</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Condemned</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $e)
            <tr>
                <td>{{ $e->ticket_number }}</td>
                <td>{{ $e->property_no }}</td>
                <td>{{ $e->item_name }}</td>
                <td>{{ $e->title }}</td>
                <td>{{ \Illuminate\Support\Str::limit($e->description, 40) }}</td>
                <td>{{ $e->equipment_type }}</td>
                <td>{{ $e->brand_model }}</td>
                <td>{{ $e->serial_no }}</td>
                <td>{{ $e->category }}</td>
                <td>{{ $e->department }}</td>
                <td>{{ $e->it_personnel }}</td>
                <td>{{ $e->client_name }}</td>
                <td>{{ $e->priority }}</td>
                <td>{{ $e->contact }}</td>
                <td>{{ $e->status }}</td>
                <td>{{ $e->date_submitted ? \Carbon\Carbon::parse($e->date_submitted)->format('M d, Y') : 'N/A' }}</td>
                <td>{{ $e->date_condemned ? \Carbon\Carbon::parse($e->date_condemned)->format('M d, Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>