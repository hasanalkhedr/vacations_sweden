<?php

namespace App\Http\Controllers\Departments;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequests\StoreDepartmentRequest;
use App\Http\Requests\DepartmentRequests\UpdateDepartmentRequest;
use App\Models\Department;
use App\Models\Employee;
use Spatie\Permission\Models\Role;

class DepartmentController extends Controller
{
    public function create()
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $validate = $request->validated();
        $department = Department::create($validate);
        $department->save();
        return redirect()->route('departments.index');
    }

    public function index()
    {
        return view('departments.index', [
            'departments' => Department::search(request(['search']))->paginate(10)
        ]);
    }

    public function show(Department $department)
    {
        return view('departments.show', [
            'department' => $department,
            'employees' => $department->employees,
            'manager' => $department->manager ?? null // Handle null manager
        ]);
    }


    public function edit(Department $department)
    {
        return view('departments.edit', ['department' => $department, 'employees' => $department->employees]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $validated = $request->validated();

        // Handle the case where the old manager might not exist
        $old_supervisor = Employee::where('id', $department->manager_id)->first();
        if ($old_supervisor) {
            $isOldSupervisorInOtherDepartments = Department::whereManagerId($old_supervisor->id)->count() > 1;
            if (!$isOldSupervisorInOtherDepartments) {
                $old_supervisor->is_supervisor = false;
                $old_supervisor->save();
            }
        }

        // Update the department
        $department->update($validated);

        // Handle the case where the new manager might not exist
        $new_supervisor = Employee::where('id', $department->manager_id)->first();
        if ($new_supervisor) {
            $new_supervisor->is_supervisor = true;
            $new_supervisor->save();
        }

        return back();
    }


    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index');
    }
}
