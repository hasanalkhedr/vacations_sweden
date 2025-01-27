<?php

namespace App\Helpers;

use App\Models\BlockedDay;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;

class Helper
{
    public function checkIfNormalEmployee($user)
    {
        return ($user->hasExactRoles('employee') && $user->is_supervisor == false);
    }

    public function getHolidays()
    {
        $holidays = Holiday::all();
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $period = CarbonPeriod::create($holiday->from, $holiday->to);
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $holiday_dates))
                    $holiday_dates[] = $date->toDateString();
            }
        }
        return $holiday_dates;
    }

    public function getBlockeddays()
    {
        $blocked_days = BlockedDay::all();
        $blocked_day_dates = [];
        foreach ($blocked_days as $blocked_day) {
            $period = CarbonPeriod::create($blocked_day->from, $blocked_day->to);
            foreach ($period as $date) {
                if (!in_array($date->toDateString(), $blocked_day_dates))
                    $blocked_day_dates[] = $date->toDateString();
            }
        }
        return $blocked_day_dates;
    }

    public function isHoliday($date)
    {
        $holidays = $this->getHolidays();
        return (in_array($date, $holidays));
    }

    public function isBlockedDay($date)
    {
        $blocked_days = $this->getBlockeddays();
        return (in_array($date, $blocked_days));
    }

    public function isWeekend($date, $employee)
    {
        return in_array(date('N', strtotime($date)), $employee->weekdays_off);
    }

    public function getRoleIds()
    {
        $roles_ids = [];
        $roles = Role::all();
        foreach ($roles as $role) {
            $roles_ids[] = $role->id;
        }
        return $roles_ids;
    }
}
