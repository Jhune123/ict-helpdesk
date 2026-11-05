<?php

namespace App\Http\Controllers;

use App\Mail\LeaveApprovedMail;
use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);

        $applications = LeaveApplication::with(['user', 'reviewer'])
            ->when(! $isAdmin, fn ($query) => $query->where('user_id', $user->id))
            ->latest('filed_at')
            ->latest('id')
            ->paginate(15);

        $staff = collect();
        $balances = collect();
        $usedCredits = collect();
        $itStaff = collect();
        $staffLeaveTotals = collect();
        $currentStaffLeaveTotals = ['monthly' => 0, 'yearly' => 0];
        $currentBalance = null;
        $currentUsage = [];
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $yearStart = $now->copy()->startOfYear();
        $yearEnd = $now->copy()->endOfYear();

        if ($isAdmin) {
            $staff = User::role(['admin', 'it_staff'])->with('department')->orderBy('name')->get();
            $balances = LeaveBalance::whereIn('user_id', $staff->pluck('id'))->get()->keyBy('user_id');
            $usedCredits = $this->approvedCreditUsage($staff->pluck('id'));
            $itStaff = $staff->filter(fn (User $staffMember) => $staffMember->hasRole('it_staff'));
            $staffLeaveTotals = $this->approvedLeaveTotals(
                $itStaff->pluck('id'),
                $monthStart,
                $monthEnd,
                $yearStart,
                $yearEnd
            );
        } else {
            $currentBalance = LeaveBalance::where('user_id', $user->id)->first();
            $currentUsage = $this->approvedCreditUsage(collect([$user->id]))->get($user->id, []);
            $currentStaffLeaveTotals = $this->approvedLeaveTotals(
                collect([$user->id]),
                $monthStart,
                $monthEnd,
                $yearStart,
                $yearEnd
            )->get($user->id, $currentStaffLeaveTotals);
        }

        return view('staff.leaves.index', compact(
            'applications',
            'balances',
            'currentBalance',
            'currentUsage',
            'currentStaffLeaveTotals',
            'isAdmin',
            'itStaff',
            'staff',
            'usedCredits',
            'staffLeaveTotals',
            'now'
        ));
    }

    private function approvedLeaveTotals(
        Collection $userIds,
        Carbon $monthStart,
        Carbon $monthEnd,
        Carbon $yearStart,
        Carbon $yearEnd
    ): Collection {
        $totals = $userIds->mapWithKeys(fn ($userId) => [
            $userId => ['monthly' => 0, 'yearly' => 0],
        ]);

        if ($userIds->isEmpty()) {
            return $totals;
        }

        LeaveApplication::query()
            ->whereIn('user_id', $userIds)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $yearEnd->toDateString())
            ->whereDate('end_date', '>=', $yearStart->toDateString())
            ->get()
            ->each(function (LeaveApplication $leave) use ($totals, $monthStart, $monthEnd, $yearStart, $yearEnd) {
                $userTotals = $totals->get($leave->user_id);
                $userTotals['monthly'] += $leave->workingDaysWithin($monthStart, $monthEnd);
                $userTotals['yearly'] += $leave->workingDaysWithin($yearStart, $yearEnd);
                $totals->put($leave->user_id, $userTotals);
            });

        return $totals;
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $validated = $request->validate([
            'applicant_last_name' => ['required', 'string', 'max:255'],
            'applicant_first_name' => ['required', 'string', 'max:255'],
            'applicant_middle_name' => ['nullable', 'string', 'max:255'],
            'applicant_signature' => ['required', 'string', 'max:255'],
            'office_department' => ['nullable', 'string', 'max:255'],
            'filed_at' => ['required', 'date'],
            'position' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'monthly_basic_salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'leave_type' => ['required', Rule::in(LeaveApplication::TYPES)],
            'details' => ['nullable', 'string', 'max:5000'],
            'vacation_location' => [
                'nullable',
                Rule::in(['Within the Philippines', 'Abroad']),
            ],
            'vacation_destination' => [
                'nullable',
                'string',
                'max:255',
            ],
            'vacation_within_philippines' => [
                Rule::requiredIf(fn () => in_array($request->input('leave_type'), [
                    'Vacation Leave',
                    'Special Privilege Leave',
                ], true) && ! $request->boolean('vacation_abroad')),
                'boolean',
            ],
            'vacation_within_destination' => [
                'nullable',
                'string',
                'max:255',
            ],
            'vacation_abroad' => ['sometimes', 'boolean'],
            'vacation_abroad_destination' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('vacation_abroad')),
            ],
            'sick_treatment' => [
                'nullable',
                Rule::in(['In Hospital', 'Out Patient']),
            ],
            'sick_illness' => [
                'nullable',
                'string',
                'max:255',
            ],
            'sick_in_hospital' => [
                Rule::requiredIf(fn () => $request->input('leave_type') === 'Sick Leave'
                    && ! $request->boolean('sick_out_patient')),
                'boolean',
            ],
            'sick_in_hospital_illness' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('sick_in_hospital')),
            ],
            'sick_out_patient' => ['sometimes', 'boolean'],
            'sick_out_patient_illness' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->boolean('sick_out_patient')),
            ],
            'sick_additional_details' => ['nullable', 'string', 'max:1000'],
            'special_leave_illness' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->input('leave_type') === 'Special Leave Benefits for Women'),
            ],
            'special_leave_additional_details' => ['nullable', 'string', 'max:1000'],
            'study_purpose' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'study_completion' => ['sometimes', 'boolean'],
            'study_bar_review' => ['sometimes', 'boolean'],
            'study_other_purpose_selected' => ['sometimes', 'boolean'],
            'study_monetization' => ['sometimes', 'boolean'],
            'study_terminal_leave' => ['sometimes', 'boolean'],
            'other_leave_type' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->input('leave_type') === 'Others'),
            ],
            'other_purpose' => [
                'nullable',
                'string',
                'max:1000',
                Rule::requiredIf(fn () => $request->boolean('study_other_purpose_selected')),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'commutation_requested' => ['required', 'boolean'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->startOfDay();
        $workingDays = LeaveApplication::workingDaysBetween($start, $end);

        if ($workingDays < 1) {
            return back()->withErrors([
                'start_date' => 'The selected dates must include at least one weekday.',
            ])->withInput();
        }

        $validated['details'] = collect([
            $validated['vacation_location'] ?? null
                ? $validated['vacation_location'].': '.($validated['vacation_destination'] ?? '')
                : null,
            $request->boolean('vacation_within_philippines')
            ? 'Within the Philippines: '.($validated['vacation_within_destination'] ?? '')
                : null,
            $request->boolean('vacation_abroad')
            ? 'Abroad: '.($validated['vacation_abroad_destination'] ?? '')
                : null,
            $validated['sick_treatment'] ?? null
            ? $validated['sick_treatment'].' — '.($validated['sick_illness'] ?? '')
                : null,
            $request->boolean('sick_in_hospital')
            ? 'In Hospital — '.($validated['sick_in_hospital_illness'] ?? '')
            : null,
            $request->boolean('sick_out_patient')
            ? 'Out Patient — '.($validated['sick_out_patient_illness'] ?? '')
            : null,
            $validated['sick_additional_details'] ?? null,
            $validated['special_leave_illness'] ?? null
            ? 'Specified illness: '.$validated['special_leave_illness']
            : null,
            $validated['special_leave_additional_details'] ?? null,
            $validated['study_purpose'] ?? null
            ? 'Study purpose: '.$validated['study_purpose']
            : null,
            $request->boolean('study_completion') ? "Completion of Master's Degree / Full-time Study Leave" : null,
            $request->boolean('study_bar_review') ? 'BAR/Board Examination Review' : null,
            $validated['other_purpose'] ?? null
            ? 'Other purpose: '.$validated['other_purpose']
            : null,
            $request->boolean('study_monetization') ? 'Monetization of Leave Credits' : null,
            $request->boolean('study_terminal_leave') ? 'Terminal Leave' : null,
            $validated['other_leave_type'] ?? null
                ? 'Other leave type: '.$validated['other_leave_type']
                : null,
            $validated['other_purpose'] ?? null
                ? 'Other purpose: '.$validated['other_purpose']
                : null,
            $validated['details'] ?? null,
        ])->filter()->implode("\n");
        $validated['details'] = $validated['details'] ?: 'Leave application submitted.';

        $hasOverlap = LeaveApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<=', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->exists();

        if ($hasOverlap) {
            return back()->withErrors([
                'start_date' => 'You already have a pending or approved leave application that overlaps these dates.',
            ])->withInput();
        }

        $validated['user_id'] = $user->id;
        $validated['filed_at'] = Carbon::parse($validated['filed_at'])->toDateString();
        $validated['working_days'] = $workingDays;
        $validated['status'] = 'pending';

        LeaveApplication::create($validated);

        return redirect()->route('staff.leaves.index')->with('success', 'Your leave application was submitted for review.');
    }

    public function review(Request $request, LeaveApplication $leave): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        $isOwnITStaffApplication = ($user->hasRole('it_staff') || $user->role === 'it_staff')
            && $leave->user_id === $user->id;

        abort_unless($isAdmin || $isOwnITStaffApplication, 403);

        $allowedStatuses = $isAdmin ? ['approved', 'disapproved'] : ['approved'];
        $validated = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leave applications can be reviewed.');
        }

        $leave->update([
            'status' => $validated['status'],
            'review_notes' => $validated['review_notes'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if ($validated['status'] === 'approved') {
            try {
                $this->sendApprovalNotice($leave->fresh(['user']));
            } catch (\Throwable $exception) {
                Log::error('Leave was approved, but the staff notification email could not be sent.', [
                    'leave_application_id' => $leave->id,
                    'exception' => $exception->getMessage(),
                ]);

                return back()->with('error', 'The leave application was approved, but the notification email could not be sent. Please contact the system administrator.');
            }
        }

        return back()->with('success', 'The leave application was '.$validated['status'].'.');
    }

    public function show(LeaveApplication $leave): View
    {
        $this->authorizeView($leave);
        $leave->load(['user', 'reviewer']);

        return view('staff.leaves.show', compact('leave'));
    }

    public function edit(LeaveApplication $leave): View
    {
        $this->authorizeEdit($leave);

        return view('staff.leaves.edit', compact('leave'));
    }

    public function update(Request $request, LeaveApplication $leave): RedirectResponse
    {
        $this->authorizeEdit($leave);

        $validated = $request->validate([
            'applicant_last_name' => ['required', 'string', 'max:255'],
            'applicant_first_name' => ['required', 'string', 'max:255'],
            'applicant_middle_name' => ['nullable', 'string', 'max:255'],
            'applicant_signature' => ['required', 'string', 'max:255'],
            'office_department' => ['nullable', 'string', 'max:255'],
            'filed_at' => ['required', 'date'],
            'position' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'monthly_basic_salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'leave_type' => ['required', Rule::in(LeaveApplication::TYPES)],
            'other_leave_type' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(fn () => $request->input('leave_type') === 'Others'),
            ],
            'details' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'commutation_requested' => ['required', 'boolean'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->startOfDay();
        $workingDays = LeaveApplication::workingDaysBetween($start, $end);
        if ($workingDays < 1) {
            return back()->withErrors([
                'start_date' => 'The selected dates must include at least one weekday.',
            ])->withInput();
        }

        $overlap = LeaveApplication::where('user_id', $leave->user_id)
            ->where('id', '!=', $leave->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<=', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->exists();
        if ($overlap) {
            return back()->withErrors([
                'start_date' => 'These dates overlap another pending or approved leave application for this staff member.',
            ])->withInput();
        }

        $leave->update([
            ...$validated,
            'working_days' => $workingDays,
            'details' => $validated['details'] ?: 'Leave application submitted.',
        ]);

        return redirect()->route('staff.leaves.index')->with('success', 'Leave application updated.');
    }

    public function destroy(LeaveApplication $leave): RedirectResponse
    {
        abort_unless($this->isAdmin(Auth::user()), 403);

        $leave->delete();

        return redirect()->route('staff.leaves.index')->with('success', 'Leave application deleted.');
    }

    public function print(LeaveApplication $leave): View
    {
        $this->authorizeView($leave);
        $leave->load(['user', 'reviewer']);

        return view('staff.leaves.print', compact('leave'));
    }

    private function authorizeView(LeaveApplication $leave): void
    {
        $user = Auth::user();
        abort_unless($this->isAdmin($user) || $leave->user_id === $user->id, 403);
    }

    private function authorizeEdit(LeaveApplication $leave): void
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        $isOwner = $leave->user_id === $user->id;

        abort_unless($isAdmin || ($isOwner && $leave->status === 'pending'), 403);
    }

    private function sendApprovalNotice(LeaveApplication $leave): void
    {
        $itStaffEmails = User::query()
            ->where(function ($query) {
                $query->where('role', 'it_staff')
                    ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'it_staff'));
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->unique()
            ->values()
            ->all();

        $adminEmails = User::query()
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'admin'));
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->pluck('email')
            ->push($leave->reviewer?->email)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($adminEmails === [] && $itStaffEmails === []) {
            throw new \RuntimeException('No email recipients are configured for leave approval notices.');
        }

        $mail = Mail::to($adminEmails ?: $itStaffEmails);
        if ($adminEmails !== [] && $itStaffEmails !== []) {
            $mail->bcc(collect($itStaffEmails)->diff($adminEmails)->all());
        }
        $mail->send(new LeaveApprovedMail($leave));
    }

    public function updateBalance(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->hasAnyRole(['admin', 'it_staff']), 404);

        $validated = $request->validate([
            'mandatory_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'sick_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'special_privilege_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'wellness_break_days' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ]);

        LeaveBalance::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return back()->with('success', 'Leave credits were updated for '.$user->name.'.');
    }

    private function approvedCreditUsage(Collection $userIds): Collection
    {
        return LeaveApplication::query()
            ->whereIn('user_id', $userIds)
            ->where('status', 'approved')
            ->selectRaw('user_id, leave_type, SUM(working_days) as days_used')
            ->groupBy('user_id', 'leave_type')
            ->get()
            ->groupBy('user_id')
            ->map(function ($applications) {
                $usage = ['mandatory' => 0, 'sick' => 0, 'special_privilege' => 0, 'wellness_break' => 0, 'cto' => 0];

                foreach ($applications as $application) {
                    if ($application->leave_type === 'Mandatory/Forced Leave') {
                        $usage['mandatory'] += (int) $application->days_used;
                    } elseif ($application->leave_type === 'Sick Leave') {
                        $usage['sick'] += (int) $application->days_used;
                    } elseif ($application->leave_type === 'Special Privilege Leave') {
                        $usage['special_privilege'] += (int) $application->days_used;
                    } elseif ($application->leave_type === 'Others') {
                        $usage['wellness_break'] += (int) $application->days_used;
                    } elseif ($application->leave_type === 'Others (CTO)') {
                        $usage['cto'] += (int) $application->days_used;
                    }
                }

                return $usage;
            });
    }

    private function isAdmin(User $user): bool
    {
        return $user->hasRole('admin') || $user->role === 'admin';
    }
}
