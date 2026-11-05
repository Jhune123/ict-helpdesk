<div class="z-10 mb-10 flex w-full max-w-6xl flex-col items-center gap-5 px-6">
    <a href="{{ route('staff.leaves.index') }}"
       class="group inline-flex w-full items-center justify-between gap-4 rounded-xl border border-green-200 bg-white px-5 py-4 text-left shadow-md transition hover:-translate-y-0.5 hover:border-green-400 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300 sm:max-w-xl"
       aria-label="Open Staff Availability and Leave Monitor">
        <span class="flex items-center gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-100 text-xl" aria-hidden="true">🗓️</span>
            <span>
                <span class="block text-xs font-bold uppercase tracking-[0.16em] text-green-700">ICT Department</span>
                <span class="mt-0.5 block text-base font-extrabold text-gray-900 sm:text-lg">Staff Availability &amp; Leave Monitor</span>
            </span>
        </span>
        <span class="shrink-0 rounded-lg bg-green-800 px-3 py-2 text-sm font-bold text-white transition group-hover:bg-green-900">
            Open <span aria-hidden="true">→</span>
        </span>
    </a>
    <section class="w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md" aria-labelledby="staff-leave-totals-heading">
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 id="staff-leave-totals-heading" class="font-bold text-gray-900">IT Staff Approved Leave Totals</h2>
            <p class="mt-1 text-sm text-gray-500">Approved working days for {{ $now->format('F Y') }} and the {{ $now->format('Y') }} calendar year.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">IT Staff</th>
                        <th class="px-5 py-3">Monthly Total (days)</th>
                        <th class="px-5 py-3">Yearly Total (days)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($staffLeaveTotals as $staffTotals)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-3 font-semibold text-gray-900">{{ $staffTotals['user']->name }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $staffTotals['monthly'] }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $staffTotals['yearly'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-4 text-center text-gray-500">No IT Staff accounts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
