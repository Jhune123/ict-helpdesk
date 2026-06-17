<!-- Example snippets for your new csm-summary.blade.php -->
<div class="hl-value">{{ $totalRespondents }}</div>

<!-- Client Types Breakdown -->
<tr><td>Citizen</td><td>{{ $clientTypes['Citizen'] ?? 0 }}</td></tr>

<!-- SQD Averages -->
<tr><td>SQD0: Overall Satisfaction</td><td class="score">{{ number_format($sqdAverages['sqd0'], 1) }} / 5.0</td></tr>