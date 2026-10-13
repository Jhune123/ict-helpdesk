<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Attendance Slip - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-gray-900" onload="window.print()">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        <div class="no-print mb-6 text-right">
            <button onclick="window.print()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow">
                🖨️ Print Report
            </button>
        </div>

        <!-- Official Header -->
        <div class="flex items-center justify-center gap-4 border-b pb-4 mb-6">
            <img src="{{ asset('image/school-logo.jpg') }}" alt="Kalinga State University logo" class="h-16 w-16 object-contain">
            <div class="text-center">
                <h1 class="text-xl font-black uppercase text-emerald-900">Kalinga State University</h1>
                <h2 class="text-sm font-bold text-gray-600">OJT Daily Attendance Log Slip</h2>
                <p class="text-xs text-gray-500 mt-1">Date: {{ \Carbon\Carbon::parse($today)->format('F d, Y') }}</p>
            </div>
        </div>

        <!-- Student Info -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase">Intern Name</p>
                <p class="font-extrabold text-base">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase">Department</p>
                <p class="font-extrabold text-base">{{ $user->department->name ?? 'Unassigned' }}</p>
            </div>
        </div>

        <!-- Time Table -->
        <table class="w-full text-center border-collapse border border-gray-300 mb-6 text-sm">
            <thead>
                <tr class="bg-emerald-900 text-white">
                    <th class="border p-2">AM IN</th>
                    <th class="border p-2">AM OUT</th>
                    <th class="border p-2">PM IN</th>
                    <th class="border p-2">PM OUT</th>
                    <th class="border p-2">Rendered Hrs</th>
                </tr>
            </thead>
            <tbody>
                <tr class="font-bold">
                    <td class="border p-2.5">{{ $attendance?->am_in ? \Carbon\Carbon::parse($attendance->am_in)->format('h:i A') : '--:--' }}</td>
                    <td class="border p-2.5">{{ $attendance?->am_out ? \Carbon\Carbon::parse($attendance->am_out)->format('h:i A') : '--:--' }}</td>
                    <td class="border p-2.5">{{ $attendance?->pm_in ? \Carbon\Carbon::parse($attendance->pm_in)->format('h:i A') : '--:--' }}</td>
                    <td class="border p-2.5">{{ $attendance?->pm_out ? \Carbon\Carbon::parse($attendance->pm_out)->format('h:i A') : '--:--' }}</td>
                    <td class="border p-2.5 text-emerald-700">{{ \App\Models\OjtAttendance::formatDuration($renderedMinutes) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-12 text-center text-xs font-bold">
            <div>
                <div class="border-b border-gray-400 mb-1 w-3/4 mx-auto"></div>
                <p>Intern Signature</p>
            </div>
            <div>
                <div class="border-b border-gray-400 mb-1 w-3/4 mx-auto"></div>
                <p>OJT Supervisor Signature</p>
            </div>
        </div>
    </div>
</body>
</html>
