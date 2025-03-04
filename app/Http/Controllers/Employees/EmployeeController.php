<?php

namespace App\Http\Controllers\Employees;

use App\Helpers\Helper;
use App\Http\Requests\EmployeesRequests\AuthenticateEmployeeRequest;
use App\Http\Requests\EmployeesRequests\StoreEmployeeRequest;
use App\Http\Requests\EmployeesRequests\UpdateEmployeePasswordRequest;
use App\Http\Requests\EmployeesRequests\UpdateEmployeeProfileRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Services\OvertimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;

class EmployeeController
{
    public function login() {
        return view('employees.login');
    }
    public function authenticate(AuthenticateEmployeeRequest $request) {
        $validated = $request->validated();

        if (auth()->attempt($validated)) {
            $employee = auth()->user();
            $request->session()->regenerate();
            if($employee->is_supervisor || $employee->hasRole(['human_resource', 'sg', 'head'])){
                return redirect()->route('leaves.index');
            }
            else {
                return redirect()->route('leaves.submitted');
            }
        }
        else {
            return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
        }
    }

    public function create() {
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.create', ['departments' => $departments, 'roles' => $roles]);
    }

    public function store(StoreEmployeeRequest $request) {
        $userLimit = Config::get('app.user_limit');
        $currentUserCount = Employee::count();

        if ($userLimit && $currentUserCount >= $userLimit) {
            return back()->withErrors(['limit' => 'User creation limit reached.']);
        }

        $validated = $request->validated();
        $employee = Employee::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'weekdays_off' => stringToIntArray($request->weekdays_off),
//            'phone_number' => $validated['phone_number'],
        ]);
        $employee->phone_number = $request->phone_number;
        foreach ($request->role_ids as $role_id) {
            $employee->assignRole(Role::findById($role_id)->name);
        }
        if($request->has('can_submit_requests')) {
            if($request->nb_of_days) {
                $employee->nb_of_days = $request->nb_of_days;
            }
            if($request->overtime_minutes) {
                $employee->overtime_minutes = $request->overtime_minutes;
            }
        }

        $roles = $employee->getRoleNames();
        foreach ($roles as $role) {
            $roles_names[] = $role;
        }
        if(in_array('employee', $roles_names)){
            $employee['department_id'] = $request['department_id'];
        }
        else {
            $employee['department_id'] = NULL;
        }

        $employee->can_submit_requests = $request->has('can_submit_requests');
        $employee->can_receive_emails = $request->has('can_receive_emails');
        $employee->bypass_officers = $request->has('bypass_officers'); // Add this line

        if($request->hasFile('profile_photo')) {
            $employee['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $employee->save();

        return redirect()->route('employees.index');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Prevent 302 loops
    
        if (!config('app.debug')) {
            // Redirect to external URL only in production (when APP_DEBUG is false)
            return redirect()->away('https://phare-if.com');
        }
    
        // Redirect to local login page in development
        return redirect('/login');
    }    

    public function index() {
        $user = auth()->user();
        $helper = new Helper();
        if($helper->checkIfNormalEmployee($user)) {
            return back();
        }
        $employees = new EmployeeService();
        $departments = Department::all();
        $roles = Role::all();
        $rolesMultiSelect = Role::get(['name', 'id'])->toArray();
        $oldkeyName = 'name';
        $newkeyName = 'label';
        $oldkeyId = 'id';
        $newkeyId = 'value';
        $i = 0;
        foreach ($rolesMultiSelect as $roleMultiSelectSingle) {
            $arrayKeys = array_keys($roleMultiSelectSingle);
            //Replace the key in our $arrayKeys array.
            $oldKeyIndexName = array_search($oldkeyName, $arrayKeys);
            $oldKeyIndexId = array_search($oldkeyId, $arrayKeys);
            $arrayKeys[$oldKeyIndexName] = $newkeyName;
            $arrayKeys[$oldKeyIndexId] = $newkeyId;
            //Combine them back into one array.
            $newArray = array_combine($arrayKeys, $roleMultiSelectSingle);
            $rolesMultiSelect[$i] = $newArray;
            $i += 1;
        }
        return view('employees.index', [
            'employees' => $employees->getAppropriateEmployees(),
            'departments' => $departments,
            'roles' => $roles,
            'rolesMultiSelect' => json_encode($rolesMultiSelect)
        ]);
    }

    public function show(Employee $employee)
    {
        $employee_service = new EmployeeService();
        $normal_pending_days = $employee_service->getNormalNbofDaysPending($employee);
        $normal_accepted_days = $employee_service->getNormalNbofDaysAccepted($employee);
        $departments = Department::all();
        $roles = Role::all();
        $loggedInUser = auth()->user();
        $overtimeService = new OvertimeService();
        $overtimeDays = $overtimeService->overtimeToLeaveDays($employee);
        $overtimeTotalTime = $overtimeService->fetchOvertimes($employee->id)['total_time'];
        if($loggedInUser->hasRole(['human_resource', 'sg','head']) || $loggedInUser->id === $employee->id || ($loggedInUser->is_supervisor && $employee->department->manager_id == $loggedInUser->id)) {
            return view('employees.show', [
                'employee' => $employee,
                'departments' => $departments,
                'roles' => $roles,
                'normal_pending_days' => $normal_pending_days,
                'normal_accepted_days' => $normal_accepted_days,
                'overtimeTotalTime' => $overtimeTotalTime,
                'overtimeDays' => $overtimeDays
            ]);
        }
        return back();
    }

    public function editProfile(Employee $employee)
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('employees.edit-profile', ['employee' => $employee, 'departments' => $departments, 'roles' => $roles]);
    }

    public function updateProfile(UpdateEmployeeProfileRequest $request, Employee $employee)
    {
        $employee_service = new EmployeeService();
        $validated = $request->validated();
        $employee->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'weekdays_off' => stringToIntArray($request->weekdays_off),
//            'phone_number' => $validated['phone_number'],
        ]);
        $employee->phone_number = $request->phone_number;
        if($request->has('can_submit_requests')) {
            $employee->update([
                'nb_of_days' => $validated['nb_of_days'],
                'overtime_minutes' => $validated['overtime_minutes'],
            ]);
        }
        foreach ($request->role_ids as $role_id) {
            $role_names[] = Role::findById($role_id)->name;
        }
        $employee->syncRoles($role_names);
        $roles = $employee->getRoleNames();
        foreach ($roles as $role) {
            $roles_names[] = $role;
        }

        if(in_array('employee', $roles_names)){
            if($employee->department_id != $request['department_id'] && $employee->id == $employee->department->manager_id){
                $employee_service->assignNewSupervisorIfCurrentChanges($employee, $request->manager_id);
            }
        }
        else {
            if($employee->department && $employee->id == $employee->department->manager_id){
                $employee_service->assignNewSupervisorIfCurrentChanges($employee, $request->manager_id);
            }
        }
        $employee['department_id'] = $request['department_id'];

        $employee->can_submit_requests = $request->has('can_submit_requests');
        $employee->can_receive_emails = $request->has('can_receive_emails');
        $employee->bypass_officers = $request->has('bypass_officers'); // Add this line

        if($request->hasFile('profile_photo')) {
            if($employee->profile_photo) {
                File::delete(public_path('storage/' . $employee->profile_photo));
            }
            $employee['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        else {
            if($employee->profile_photo && $request->is_deleted == 1) {
                File::delete(public_path('storage/' . $employee->profile_photo));
                $employee['profile_photo'] = NULL;
            }
        }

        $employee->save();
        return back();
    }

    public function editPassword(Employee $employee)
    {
        if(auth()->user()->hasRole('human_resource') || auth()->user()->id == $employee->id) {
            return view('employees.edit-password', ['employee' => $employee]);
        }
        else {
            return back();
        }
    }

    public function updatePassword(UpdateEmployeePasswordRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $employee->update(['password' => Hash::make($validated['password'])]);
        return redirect()->route('employees.show', ['employee' => $employee]);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index');
    }

}
