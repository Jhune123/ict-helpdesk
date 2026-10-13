<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Attendance Summary - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 text-gray-900" onload="window.print()">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md border border-gray-200">
        <div class="no-print mb-6 text-right">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow">
                🖨️ Print Monthly Report
            </button>
        </div>

        <!-- Official Header -->
        <div class="flex items-center justify-center gap-4 border-b pb-4 mb-6">
            <img src="{{ asset('image/school-logo.jpg') }}" alt="Kalinga State University logo" class="h-16 w-16 object-contain">
            <div class="text-center">
                <h1 class="text-xl font-black uppercase text-indigo-950">Kalinga State University</h1>
                <h2 class="text-sm font-bold text-gray-600">OJT Monthly Attendance Summary Report</h2>
                <p class="text-xs text-gray-500 mt-1">Period: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</p>
            </div>
        </div>

        <!-- Student Info Header -->
        <div class="grid grid-cols-3 gap-4 mb-6 text-sm bg-gray-50 p-4 rounded-xl border border-gray-200">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase">Intern Name</p>
                <p class="font-extrabold text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase">Department</p>
                <p class="font-extrabold text-gray-900">{{ $user->department->name ?? 'Unassigned' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase">Monthly Rendered Total</p>
                <p class="font-black text-indigo-700 text-base">{{ \App\Models\OjtAttendance::formatDuration($monthlyTotalMinutes) }} / {{ $user->required_hours ?? 486 }} hrs</p>
            </div>
        </div>

        <!-- Monthly Logs Table -->
        <table class="w-full text-center border-collapse border border-gray-300 mb-8 text-xs">
            <thead>
                <tr class="bg-indigo-900 text-white font-bold">
                    <th class="border p-2">Date</th>
                    <th class="border p-2">AM IN</th>
                    <th class="border p-2">AM OUT</th>
                    <th class="border p-2">PM IN</th>
                    <th class="border p-2">PM OUT</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $log)
                    <tr class="hover:bg-gray-50 border-b">
                        <td class="border p-2 font-bold">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y (D)') }}</td>
                        <td class="border p-2">{{ $log->am_in ? \Carbon\Carbon::parse($log->am_in)->format('h:i A') : '--:--' }}</td>
                        <td class="border p-2">{{ $log->am_out ? \Carbon\Carbon::parse($log->am_out)->format('h:i A') : '--:--' }}</td>
                        <td class="border p-2">{{ $log->pm_in ? \Carbon\Carbon::parse($log->pm_in)->format('h:i A') : '--:--' }}</td>
                        <td class="border p-2">{{ $log->pm_out ? \Carbon\Carbon::parse($log->pm_out)->format('h:i A') : '--:--' }}</td>
                        <td class="border p-2 font-semibold text-gray-700">{{ $log->status }}</td>
                        <td class="border p-2 font-black text-emerald-700">{{ \App\Models\OjtAttendance::formatDuration($log->calculateRenderedMinutes()) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500 italic">No attendance logs found for this month.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-12 pt-10 text-center text-xs font-bold">
            <div>
                <div class="border-b border-gray-400 mb-1 w-2/3 mx-auto"></div>
                <p>Intern Signature</p>
            </div>
            <div>
                <div class="border-b border-gray-400 mb-1 w-2/3 mx-auto"></div>
                <p>OJT Supervisor Signature</p>
            </div>
        </div>
    </div>
</body>
</html>
