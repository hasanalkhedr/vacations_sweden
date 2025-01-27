<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Employee;

class EmployeeService
{
    const PENDING_STATUS = 0;
    const ACCEPTED_STATUS = 1;
    const REJECTED_STATUS = 2;

    public function getAppropriateEmployees()
    {
        $loggedInUser = auth()->user();
        if ($loggedInUser->hasRole('human_resource') || $loggedInUser->hasRole(['human_resource', 'sg', 'head'])) {
            return Employee::search(request(['search']))->whereNot('id', $loggedInUser->id)->paginate(10);
        } else {
            return Employee::whereHas('department', function ($q) use ($loggedInUser) {
                $q->where('manager_id', $loggedInUser->id);
            })->search(request(['search']))->whereNot('id', $loggedInUser->id)->paginate(10);
        }
    }

    public function assignNewSupervisorIfCurrentChanges($old_supervisor, $new_manager_id)
    {
        $old_supervisor->department->manager_id = $new_manager_id;
        $old_supervisor->department->save();
        $new_manager = Employee::where('id', $new_manager_id)->first();
        $new_manager->is_supervisor = true;
        $new_manager->save();
        $isOldSupervisorInOtherDepartments = Department::whereManagerId($old_supervisor->id)->count() > 1;
        if(!$isOldSupervisorInOtherDepartments) {
            $old_supervisor->is_supervisor = false;
            $old_supervisor->save();
        }
    }

    public function getNormalNbofDaysPending($employee)
    {
        $normal_pending_days = 0;
        $leave_service = new LeaveService();
        $normal_pending_leaves = $employee->leaves->where('leave_status', self::PENDING_STATUS);
        foreach ($normal_pending_leaves as $normal_pending_leave) {
            $normal_pending_days += $leave_service->findNbofDaysOff($normal_pending_leave);
        }
        return $normal_pending_days;
    }

    public function getNormalNbofDaysAccepted($employee)
    {
        $normal_accepted_days = 0;
        $leave_service = new LeaveService();
        $normal_accepted_leaves = $employee->leaves->where('leave_status', self::ACCEPTED_STATUS);
        foreach ($normal_accepted_leaves as $normal_accepted_leave) {
            $normal_accepted_days += $leave_service->findNbofDaysOff($normal_accepted_leave);
        }
        return $normal_accepted_days;
    }
}
