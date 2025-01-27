<?php

namespace App\Http\Controllers\Leaves;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequests\StoreLeaveRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveDuration;
use App\Models\LeaveType;
use App\Services\EmployeeService;
use App\Services\OvertimeService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Services\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function create()
    {
        $employee = auth()->user();
        if ($employee->can_submit_requests) {
            $employee_service = new EmployeeService();
            $helper = new Helper();

            // Get the normal pending days including all leave types
            $normal_pending_days = $employee_service->getNormalNbofDaysPending($employee);
            $normal_accepted_days = $employee_service->getNormalNbofDaysAccepted($employee);

            // Exclude recovery leaves from pending days
            $recoveryLeaveTypeId = LeaveType::where('name', 'recovery')->first()->id;
            $normal_pending_days_without_recovery = Leave::where('employee_id', $employee->id)
                ->where('leave_type_id', '!=', $recoveryLeaveTypeId)  // Exclude recovery leave
                ->where('leave_status', self::PENDING_STATUS)
                ->get()
                ->sum(function ($leave) {
                    $leaveFromDate = Carbon::createFromFormat('Y-m-d', $leave->from);
                    $leaveToDate = Carbon::createFromFormat('Y-m-d', $leave->to);
                    return $leaveFromDate->diffInDays($leaveToDate) + 1;  // Include both start and end dates
                });

            $leave_durations = LeaveDuration::all();
            $leave_types = LeaveType::all();
            $today = now();
            $substitutes = Employee::where('department_id', $employee->department_id)->where('is_supervisor', false)->get()->except($employee->id);
            $leave_service = new LeaveService();
            $disabledDates = $leave_service->getDisabledDates($employee);
            $holidayDates = $helper->getHolidays();
            $blockedDates = $helper->getBlockeddays();

            $overtimeService = new OvertimeService();
            $overtimeDays = $overtimeService->overtimeToLeaveDays($employee);
            $overtimeTotalTime = $overtimeService->fetchOvertimes($employee->id)['total_time'];

            return view('leaves.create', [
                'employee' => $employee,
                'leave_durations' => $leave_durations,
                'leave_types' => $leave_types,
                'today' => $today,
                'department' => $employee->department,
                'substitutes' => $substitutes,
                'disabled_dates' => $disabledDates,
                'holiday_dates' => $holidayDates,
                'blocked_dates' => $blockedDates,
                'normal_pending_days' => $normal_pending_days,
                'normal_pending_days_without_recovery' => $normal_pending_days_without_recovery,  // Excluding recovery
                'normal_accepted_days' => $normal_accepted_days,
                'overtimeTotalTime' => $overtimeTotalTime,
                'overtimeDays' => $overtimeDays,
            ]);
        } else {
            return back();
        }
    }


    public function store(StoreLeaveRequest $request)
    {
        $validated = $request->validated();
        $leave_service = new LeaveService();
        $disabled_dates = $leave_service->getDisabledDates(auth()->user());
        $serializedArr = serialize($disabled_dates);
        $leave = Leave::create([
            'employee_id' => auth()->user()->id,
            'leave_duration_id' => $validated['leave_duration_id'],
            'from' => $validated['from'],
            'to' => $validated['to'],
            'travelling' => $validated['travelling'],
            'leave_type_id' => $validated['leave_type_id'],
        ]);
        if ($request->hasFile('attachment_path')) {
            $leave['attachment_path'] = $request->file('attachment_path')->store('attachments', 'public');
        }
        $leave->date_of_submission = now()->format('Y/m/d');
        if ($request['substitute_employee_id']) {
            $leave->substitute_employee_id = $request['substitute_employee_id'];
        }
        $leave->disabled_dates = $serializedArr;

        if ($leave->employee->hasRole('human_resource') && $leave->employee->can_submit_requests) {
            $role = Role::findByName('sg');
            $head = Employee::role('head')->get();
            $processing_officers = Employee::role('sg')->get()->concat($head)->all();
            $leave->processing_officer_role = $role->id;
        } else if ($leave->employee->department->manager->hasRole('sg') || $leave->employee->is_supervisor || in_array($leave->employee->department->id, [2, 7, 14])) {
            $role = Role::findByName('human_resource');
            $processing_officers = Employee::role('human_resource')->get();
            $leave->processing_officer_role = $role->id;
        } else {
            $role = Role::findByName('employee');
            $processing_officers = collect([auth()->user()->department->manager]);
            $leave->processing_officer_role = $role->id;
        }

        $recoveryLeave = LeaveType::where('name', 'recovery')->first();

        if ($leave->leave_type_id == $recoveryLeave->id) {
            $leave_service->subtractOvertimeMinutes($leave);
        }

        $leave->save();
        $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers, $leave->substitute_employee);
        return redirect()->route('leaves.submitted');
    }

    public function index()
    {
        $employee = auth()->user();
        $helper = new Helper();
        if ($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if ($employee->hasRole("employee") && $employee->is_supervisor) {
            $leaves = Leave::where('processing_officer_role', Role::findByName('employee')->id)->where('leave_status', self::PENDING_STATUS)
                ->whereHas('employee', function ($q) use ($employee) {
                    $q->whereHas('department', function ($q) use ($employee) {
                        $q->where('manager_id', $employee->id);
                    });
                })
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))->paginate(10);
        }
        if ($employee->hasRole("human_resource")) {
            $leaves = Leave::whereNot('processing_officer_role', Role::findByName('sg')->id)->where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        if ($employee->hasRole(['sg', 'head'])) {
            $leaves = Leave::where('leave_status', self::PENDING_STATUS)->search(request(['search']))->paginate(10);
        }
        return view('leaves.index', [
            'leaves' => $leaves,
            'employee' => $employee,
        ]);
    }

    public function acceptedIndex()
    {
        $employee = auth()->user();
        $helper = new Helper();
        if ($helper->checkIfNormalEmployee($employee)) {
            return back();
        }

        if ($employee->hasExactRoles('employee') && $employee->is_supervisor) {
            $leaves = Leave::where(function ($query) use ($employee) {
                $query->whereHas('employee', function ($q) use ($employee) {
                    $q->whereHas('department', function ($q) use ($employee) {
                        $q->where('manager_id', $employee->id);
                    });
                })
                    ->whereNot('processing_officer_role', Role::findByName('employee')->id)
                    ->whereNot('leave_status', self::REJECTED_STATUS);
            })
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))
                ->latest()
                ->paginate(10);
        }

        if ($employee->hasRole('human_resource')) {
            $leaves = Leave::where('leave_status', self::ACCEPTED_STATUS)
                ->orWhere(function ($query) {
                    $query->where('leave_status', self::PENDING_STATUS)->where('processing_officer_role', Role::findByName('sg')->id);
                })
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))
                ->latest()
                ->paginate(10);
        }

        if ($employee->hasRole(['sg', 'head'])) {
            $leaves = Leave::whereNot('employee_id', $employee->id)
                ->where('leave_status', self::ACCEPTED_STATUS)->search(request(['search']))->latest()->paginate(10);
        }

        return view('leaves.accepted-index', [
            'leaves' => $leaves
        ]);
    }

    public function rejectedIndex()
    {
        $employee = auth()->user();
        $helper = new Helper();
        if ($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        $leaves = Leave::where('leave_status', self::REJECTED_STATUS)->where('rejected_by', $employee->id)->whereNot('employee_id', $employee->id)->search(request(['search']))->latest()->paginate(10);
        return view('leaves.rejected-index', [
            'leaves' => $leaves
        ]);
    }

    public function show(Leave $leave)
    { {
            $loggedInUser = auth()->user();
            $processing_officer = Role::where('id', $leave->processing_officer_role)->first();

            if ($leave->employee_id == $loggedInUser->id || $loggedInUser->hasRole(['human_resource', 'sg', 'head']) || $loggedInUser->id == $leave->employee->department->manager_id) {
                return view('leaves.show', [
                    'leave' => $leave,
                    'processing_officer' => $processing_officer
                ]);
            }

            return back();
        }
    }

    public function accept(Leave $leave)
    {
        $employee = auth()->user();
        $helper = new Helper();
        if ($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if ((!$employee->hasRole($leave->processing_officer->name) && !$employee->hasRole('head') && !$employee->hasRole('sg'))) {
            return back();
        }
        $leave_service = new LeaveService();
        $leave_service->checkProcessingOfficerandElevateRequest($leave);
        return redirect()->route('leaves.index');
    }

    public function reject(Request $request, Leave $leave)
    {
        $employee = auth()->user();
        $helper = new Helper();
        if (($helper->checkIfNormalEmployee($employee) || !$employee->hasRole($leave->processing_officer->name)) && !$employee->hasRole('head') && !$employee->hasRole('head')) {
            return back();
        }
        $leave_service = new LeaveService();
        $leave_service->rejectLeaveRequest($request, $leave);
        return redirect()->route('leaves.index');
    }

    public function submitted()
    {
        if (auth()->user()->can_submit_requests) {
            $leaves = Leave::where('employee_id', auth()->user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('leaves.submitted', [
                'leaves' => $leaves
            ]);
        } else {
            return back();
        }
    }

    public function getCalendarForm()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $months = [];

        for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
            $dateObj = \DateTime::createFromFormat('!m', $monthNum);
            $months[] = [$dateObj->format('m'), $dateObj->format('F')];
        }
        $departments = Department::all();
        return view('leaves.calendar-form', [
            'departments' => $departments,
            'months' => $months
        ]);
    }

    public function generateCalendar(Request $request)
    {
        $helper = new Helper();
        $year = $request->year;
        $month = Carbon::createFromDate($year, $request->month, 1);
        $month_name = $month->monthName;
        $start_of_month = $month->copy()->startOfMonth();
        $end_of_month = $month->copy()->endOfMonth();
        $period = CarbonPeriod::create($start_of_month, $end_of_month);

        // Initialize arrays
        $holidays = [];
        $dates = [];

        // Generate list of dates and holidays in the month
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            if ($helper->isHoliday($formattedDate)) {
                $holidays[] = $formattedDate;
            }
            $dates[] = $date;
        }

        // Determine which employees and leaves to fetch
        if ($request->department_id == 'all') {
            // Fetch all employees, including soft-deleted ones
            $employees = Employee::withTrashed()->get();
            $employeeIds = $employees->pluck('id')->toArray();

            // Fetch leaves for all employees excluding rejected leaves
            $leaves = Leave::with(['employee' => function ($query) {
                $query->withTrashed();
            }])
                ->whereIn('employee_id', $employeeIds)
                ->where('leave_status', '!=', self::REJECTED_STATUS)
                ->whereDate('from', '<=', $end_of_month)
                ->whereDate('to', '>=', $start_of_month)
                ->get();
        } else {
            // Determine the department based on user roles
            if (!auth()->user()->hasRole(['human_resource', 'sg', 'head'])) {
                // If not privileged, use the user's own department
                $department = Department::find(auth()->user()->department_id);
            } else {
                // If privileged, use the requested department ID
                $department = Department::find($request->department_id);
            }

            // Fetch employees from the selected department, including soft-deleted ones
            $employees = Employee::withTrashed()
                ->where('department_id', $department->id)
                ->get();
            $employeeIds = $employees->pluck('id')->toArray();

            // Fetch leaves for the employees in the selected department, excluding rejected leaves
            $leaves = Leave::with(['employee' => function ($query) {
                $query->withTrashed();
            }])
                ->whereIn('employee_id', $employeeIds)
                ->where('leave_status', '!=', self::REJECTED_STATUS)
                ->whereDate('from', '<=', $end_of_month)
                ->whereDate('to', '>=', $start_of_month)
                ->get();
        }

        // Process leaves to generate leave-date pairs
        $leaveId_dates_pairs = [];
        $employeeLeaveCounts = []; // Initialize an array to keep track of leave counts per employee

        foreach ($leaves as $leave) {
            $leavePeriod = CarbonPeriod::create($leave->from, $leave->to);
            $disabled_dates = unserialize($leave->disabled_dates);

            // Ensure we have the employee data, even if soft-deleted
            $employee = $leave->employee;

            if ($disabled_dates) {
                foreach ($leavePeriod as $date) {
                    $dateStr = $date->toDateString();
                    if (
                        !$helper->isWeekend($dateStr, $employee) &&
                        !in_array($dateStr, $disabled_dates)
                    ) {
                        $leaveId_dates_pairs[$leave->employee_id . '&' . $dateStr] = $leave;

                        // Increment leave count for the employee
                        if (isset($employeeLeaveCounts[$leave->employee_id])) {
                            $employeeLeaveCounts[$leave->employee_id]++;
                        } else {
                            $employeeLeaveCounts[$leave->employee_id] = 1;
                        }
                    }
                }
            } else {
                foreach ($leavePeriod as $date) {
                    $dateStr = $date->toDateString();
                    $leaveId_dates_pairs[$leave->employee_id . '&' . $dateStr] = $leave;

                    // Increment leave count for the employee
                    if (isset($employeeLeaveCounts[$leave->employee_id])) {
                        $employeeLeaveCounts[$leave->employee_id]++;
                    } else {
                        $employeeLeaveCounts[$leave->employee_id] = 1;
                    }
                }
            }
        }

        // Filter out soft-deleted employees who have zero leaves in the selected date range
        $employees = $employees->filter(function ($employee) use ($employeeLeaveCounts) {
            if ($employee->trashed()) {
                // If the employee is soft-deleted, include them only if they have leaves
                return isset($employeeLeaveCounts[$employee->id]);
            }
            // If the employee is active, include them
            return true;
        })->values(); // Re-index the collection

        // Return the view with the necessary data
        return view('leaves.calendar', [
            'month_name'           => $month_name,
            'year'                 => $year,
            'dates'                => $dates,
            'employees'            => $employees,
            'leaveId_dates_pairs'  => $leaveId_dates_pairs,
            'holidays'             => $holidays,
        ]);
    }


    public function downloadAttachment(Leave $leave)
    {
        $path = Storage::disk('local')->path("public/$leave->attachment_path");
        $content = file_get_contents($path);
        return response($content)->withHeaders([
            'Content-Type' => mime_content_type($path)
        ]);
    }

    public function destroy(Leave $leave)
    {
        if ($leave->employee->id != auth()->user()->id && !auth()->user()->hasRole('human_resource')) {
            return back();
        }
        $leave_service = new LeaveService();
        $recovery = LeaveType::where('name', 'recovery')->first();
        if ($leave->leave_type_id == $recovery->id) {
            $leave_service->recoverMinutes($leave);
        } else {
            if ($leave->leave_status == self::ACCEPTED_STATUS) {
                $leave_service->recoverDays($leave);
            }
            if ($leave->leave_status == self::PENDING_STATUS) {
                $processing_officers = $leave_service->getProcessingOfficersForLeaveDestroy($leave);
                $leave_service->sendEmailToInvolvedEmployees($leave, $processing_officers, $leave->substitute_employee, true);
            }
        }

        $leave->delete();
        return back();
    }

    public function createReport()
    {
        if (auth()->user()->hasRole(['human_resource', 'sg', 'head'])) {
            $employees = Employee::whereCanSubmitRequests(true)->orderBy('first_name')->get()->except(auth()->id());
            $leaveTypes = LeaveType::get();
            return view('leaves.create-report', [
                'employees' => $employees,
                'leaveTypes' => $leaveTypes,
            ]);
        } else {
            return back();
        }
    }

    public function generateReport(Request $request)
    {
        $filtered_leave_types = $request->leave_types ?? [];
        $employee_id = $request->employee_id;
        $employee = Employee::whereId($employee_id)->first();
        $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        $leave_service = new LeaveService();
        $data = $leave_service->fetchLeaves($employee_id, $filtered_leave_types, $from_date, $to_date);
        $leaves = $data['leaves'];
        unset($data['leaves']);

        return view('leaves.view-report', [
            'leaves' => $leaves,
            'employee' => $employee,
            'data' => $data
        ]);
    }
}
