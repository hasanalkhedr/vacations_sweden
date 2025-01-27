<?php

namespace App\Http\Controllers\Overtimes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Overtime;
use App\Services\OvertimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OvertimeController extends Controller
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function create() {
        $helper = new Helper();
        $employee = auth()->user();
        if(auth()->user()->can_submit_requests) {
            $holiday_dates = $helper->getHolidays();
            return view('overtimes.create',[
                'employee' => $employee,
                'holiday_dates' => $holiday_dates,
            ]);
        }
        else {
            return back();
        }

    }

    public function store(Request $request) {
        if($request->has('date')) {
            $processing_officers = [];
            $overtime_service = new OvertimeService();
            for ($i = 0; $i < count($request->date); $i++) {
                if ($request->date[$i] == NULL || $request->from[$i] == NULL || $request->to[$i] == NULL) {
                    continue;
                }
                $overtime = Overtime::create([
                    'employee_id' => auth()->user()->id,
                    'date' => $request->date[$i],
                    'from' => $request->from[$i],
                    'to' => $request->to[$i],
                    'hours' => $request->hours[$i]
                ]);
                if ($request->objective[$i]) {
                    $overtime->objective = $request->objective[$i];
                }
                $overtime->date_of_submission = now()->format('Y-m-d');

                if($overtime->employee->hasRole('human_resource') && $overtime->employee->can_submit_requests) {
                    $role = Role::findByName('sg');
                    $head = Employee::role('head')->get();
                    $processing_officers = Employee::role('sg')->get()->concat($head)->all();
                    $overtime->processing_officer_role = $role->id;
                }
                else if($overtime->employee->department->manager->hasRole('sg') || $overtime->employee->is_supervisor) {
                    $role = Role::findByName('human_resource');
                    $processing_officers = Employee::role('human_resource')->get();
                    $overtime->processing_officer_role = $role->id;
                }
                else {
                    $role = Role::findByName('employee');
                    $processing_officers = collect([auth()->user()->department->manager]);
                    $overtime->processing_officer_role = $role->id;
                }
                $overtime->save();
            }
            $overtime_service->sendEmailToInvolvedEmployees(null, $processing_officers);
        }
        return back();
    }

    public function submitted() {
        if(auth()->user()->can_submit_requests) {
            $overtimes = auth()->user()->overtimes;
            return view('overtimes.submitted', [
                'overtimes' => $overtimes
            ]);
        }
        else {
            return back();
        }
    }

    public function destroy(Overtime $overtime) {
        $overtimeService = new OvertimeService();
        if($overtime->overtime_status == self::ACCEPTED_STATUS) {
            $employee = $overtime->employee;
            $minutes = $overtimeService->getOvertimeMinutes($overtime);
            $employee->overtime_minutes -= $minutes;
            $employee->save();
        }
        $overtime->delete();
        return redirect()->route('overtimes.submitted');
    }

    public function index() {
        $employee=auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if($employee->hasRole("employee") && $employee->is_supervisor){
            $overtimes = Overtime::where('processing_officer_role', Role::findByName('employee')->id)->where('overtime_status', self::PENDING_STATUS)
                        ->whereHas('employee', function ($q) use ($employee) {
                        $q->whereHas('department', function ($q) use ($employee) {
                            $q->where('manager_id', $employee->id);
                        });
                    })
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole("human_resource")) {
            $overtimes = Overtime::whereNot('processing_officer_role', Role::findByName('sg')->id)->where('overtime_status', self::PENDING_STATUS)
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))->paginate(10);
        }
        if($employee->hasRole(['sg', 'head'])) {
            $overtimes = Overtime::where('overtime_status', self::PENDING_STATUS)
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))->paginate(10);
        }
        return view('overtimes.index', [
            'overtimes' => $overtimes,
            'employee' => $employee,
        ]);
    }

    public function acceptedIndex() {
        $employee = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        if($employee->hasExactRoles('employee') && $employee->is_supervisor) {
            $overtimes = Overtime::where(function ($query) use ($employee) {
                    $query->whereHas('employee', function ($q) use ($employee) {
                        $q->whereHas('department', function ($q) use ($employee) {
                            $q->where('manager_id', $employee->id);
                        });})
                        ->whereNot('processing_officer_role', Role::findByName('employee')->id)
                        ->whereNot('overtime_status', self::REJECTED_STATUS);})
                        ->whereNot('employee_id', $employee->id)
                        ->search(request(['search']))
                        ->latest()
                        ->paginate(10);
        }

        if($employee->hasRole('human_resource')) {
            $overtimes = Overtime::where('overtime_status', self::ACCEPTED_STATUS)
                ->orWhere(function ($query) {
                    $query->where('overtime_status', self::PENDING_STATUS)->where('processing_officer_role', Role::findByName('sg')->id);})
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))
                ->latest()
                ->paginate(10);
        }

        if($employee->hasRole(['sg', 'head'])) {
            $overtimes = Overtime::whereNot('employee_id', $employee->id)
                ->where('overtime_status', self::ACCEPTED_STATUS)
                ->whereNot('employee_id', $employee->id)
                ->search(request(['search']))
                ->latest()
                ->paginate(10);

        }

        return view('overtimes.accepted-index', [
            'overtimes' => $overtimes
        ]);
    }

    public function rejectedIndex() {
        $employee = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($employee)) {
            return back();
        }
        $overtimes = Overtime::where('overtime_status', self::REJECTED_STATUS)->where('rejected_by', $employee->id)->whereNot('employee_id', $employee->id)->search(request(['search']))->latest()->paginate(10);
        return view('overtimes.rejected-index', [
            'overtimes' => $overtimes
        ]);
    }

    public function show(Overtime $overtime) {
        {
            $loggedInUser = auth()->user();
            $processing_officer = Role::where('id', $overtime->processing_officer_role)->first();
            if($overtime->employee_id == $loggedInUser->id || $loggedInUser->hasRole(['human_resource', 'sg', 'head']) || $loggedInUser->id == $overtime->employee->department->manager_id) {
                return view('overtimes.show', [
                    'overtime' => $overtime,
                    'processing_officer' => $processing_officer
                ]);
            }
            return back();
        }
    }

    public function accept(Overtime $overtime) {
        $user = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($user)) {
            return back();
        }
        if(!$user->hasRole($overtime->processing_officer->name) && !$employee->hasRole('head')) {
            return back();
        }
        $overtime_service = new OvertimeService();
        $overtime_service->checkProcessingOfficerandElevateRequest($overtime);
        return redirect()->route('overtimes.index');
    }

    public function reject(Request $request, Overtime $overtime) {
        $user = auth()->user();
        $helper = new Helper();
        if(($helper->checkIfNormalEmployee($user) || !$user->hasRole($overtime->processing_officer->name)) && !$employee->hasRole('head')) {
            return back();
        }
        $overtime_service = new OvertimeService();
        $overtime_service->rejectOvertimeRequest($request, $overtime);
        return redirect()->route('overtimes.index');
    }

    public function createReport() {
        if(auth()->user()->hasRole(['human_resource', 'sg', 'head'])) {
            $employees = Employee::whereCanSubmitRequests(true)->orderBy('first_name')->get()->except(auth()->id());
            return view('overtimes.create-report', [
                'employees' => $employees
            ]);
        }
        else {
            return back();
        }
    }

    public function generateReport(Request $request) {
        $employee_id = $request->employee_id;
        $employee = Employee::whereId($employee_id)->first();
        $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date)->format('Y-m-d');
        $overtime_service = new OvertimeService();
        $data = $overtime_service->fetchOvertimes($employee_id, $from_date, $to_date);
        $overtimes = $data['overtimes'];
        $total_time = $data['total_time'];

        return view('overtimes.view-report', [
            'overtimes' => $overtimes,
            'employee' => $employee,
            'total_time' => $total_time
        ]);
    }
}
