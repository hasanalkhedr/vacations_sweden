<?php

namespace App\Services;

use App\Jobs\OvertimeJobs\SendOvertimeRequestAcceptedEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestIncomingEmailJob;
use App\Jobs\OvertimeJobs\SendOvertimeRequestRejectedEmailJob;
use App\Models\Employee;
use App\Models\Overtime;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class OvertimeService
{
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function sendEmailToInvolvedEmployees($overtime, $processing_officers = NULL) {
        if($overtime == null) {
            foreach ($processing_officers as $processing_officer) {
                if ($processing_officer->can_receive_emails) {
                    dispatch(new SendOvertimeRequestIncomingEmailJob($processing_officer));
                }
            }
        }
        else {
            if($overtime->overtime_status == self::ACCEPTED_STATUS) {
                $employee = Employee::where('id', $overtime->employee_id)->first();
                if ($employee->can_receive_emails) {
                    dispatch(new SendOvertimeRequestAcceptedEmailJob($employee));
                }
            }
            elseif($overtime->overtime_status == self::REJECTED_STATUS) {
                $employee = Employee::where('id', $overtime->employee_id)->first();
                if ($employee->can_receive_emails) {
                    dispatch(new SendOvertimeRequestRejectedEmailJob($employee));
                }
            }
            else {
                foreach ($processing_officers as $processing_officer) {
                    if ($processing_officer->can_receive_emails) {
                        dispatch(new SendOvertimeRequestIncomingEmailJob($processing_officer));
                    }
                }
            }
        }
    }

    public function checkProcessingOfficerandElevateRequest($overtime) {
        $employee = $overtime->employee;
        $processing_officer_role = $overtime->processing_officer_role;
        $role = Role::findById($processing_officer_role);
        switch ($role->name){
            case ('human_resource'):
                if(auth()->user()->hasRole(['sg', 'head'])) {
                    $role_sg = Role::findByName('sg');
                    $overtime->processing_officer_role = $role_sg->id;
                    $this->acceptOvertime($overtime);
                    $processing_officers = NULL;
                    break;
                }
                else {
                    $role = Role::findByName('sg');
                    $overtime->processing_officer_role = $role->id;
                    $head = Employee::role('head')->get();
                    $processing_officers = Employee::role('sg')->get()->concat($head)->all();
                }
                break;
            case ('employee'):
                if(auth()->user()->hasRole(['sg', 'head'])) {
                    $role_sg = Role::findByName('sg');
                    $overtime->processing_officer_role = $role_sg->id;
                    $this->acceptOvertime($overtime);
                    $processing_officers = NULL;
                    break;
                }
                $role = Role::findByName('human_resource');
                $overtime->processing_officer_role = $role->id;
                $processing_officers = Employee::role('human_resource')->get();
                break;
            case ('sg'):
                $this->acceptOvertime($overtime);
                $processing_officers = NULL;
                break;
        }
        $overtime->save();
       $this->sendEmailToInvolvedEmployees($overtime, $processing_officers);
    }

    public function rejectOvertimeRequest($request, $overtime) {
        $overtime->overtime_status = self::REJECTED_STATUS;
        if($request['cancellation_reason']) {
            $overtime->cancellation_reason = $request['cancellation_reason'];
        }
        $overtime->rejected_by = auth()->user()->id;
        $overtime->save();
       $this->sendEmailToInvolvedEmployees($overtime);
    }

    public function acceptOvertime($overtime) {
        $employee = $overtime->employee;
        $minutes = $this->getOvertimeMinutes($overtime);
        $employee->overtime_minutes += $minutes;
        $employee->save();
        $overtime->overtime_status = self::ACCEPTED_STATUS;
        $overtime->save();
    }

    public function getOvertimeMinutes($overtime) {
        $time = Carbon::createFromTimeString($overtime->hours);
        $start_of_day = Carbon::createFromTimeString($overtime->hours)->startOfDay();
        return $time->diffInMinutes($start_of_day);
    }

    public function fetchOvertimes($employee_id, $from_date = null, $to_date = null) {
        $employee = Employee::whereId($employee_id)->first();
        if($from_date == null && $to_date == null) {
            $overtimes = Overtime::where('employee_id', $employee_id)->where('overtime_status', self::ACCEPTED_STATUS)->get();
        }
        else {
            $overtimes = Overtime::where('employee_id', $employee_id)->where('overtime_status', self::ACCEPTED_STATUS)->whereDate('date', '>=', $from_date)->whereDate('date', '<=', $to_date)->get();
        }
        $total = $this-> getTotalOvertime($employee);
        $data['overtimes'] = $overtimes;
        $data['hours'] = $total['hours'];
        $data['mins'] = $total['mins'];
        $data['total_time'] = $total['total_time'];
        return $data;
    }

    public function getTotalOvertime(Employee $employee) {
//        $totalMinutes = 0;
//        foreach ($overtimes as $overtime) {
//            $time = Carbon::createFromTimeString($overtime->hours);
//            $start_of_day = Carbon::createFromTimeString($overtime->hours)->startOfDay();
//            $minutes = $time->diffInMinutes($start_of_day);
//            $totalMinutes += $minutes;
//        }
        $totalMinutes = $employee->overtime_minutes;
        $hours = floor($totalMinutes / 60);
        $mins = floor($totalMinutes % 60);
        $total['hours'] = (int)$hours;
        $total['mins'] = (int)$mins;
        $secs = floor($totalMinutes *60 % 60);
        $total['total_time'] = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        return $total;
    }

    public function overtimeToLeaveDays(Employee $employee) {
        $overtimeFullDayHours = 450;    // 7.5 hours
        $overtimeHalfDayHours = 225;    // 3.75 hours

        $total = $this->fetchOvertimes($employee->id);
        $totalOvertimeMinutes = $total['hours'] * 60 + $total['mins'];
        $fullDays = (int)($totalOvertimeMinutes / $overtimeFullDayHours);
        $halfDays = (int)($totalOvertimeMinutes / $overtimeHalfDayHours);
        $total_days = $fullDays + ($halfDays - 2*$fullDays)*0.5;

        return $total_days;
    }
}
