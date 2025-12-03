<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Inventory</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 5px; 
            text-align: left;
        }
        th { 
            background-color: #f0f0f0; 
        }
        @media print {
            a { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
