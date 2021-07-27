<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::leftJoin('department_employee', 'department_employee.department_id', '=', 'departments.id')
            ->leftJoin('employees', 'department_employee.employee_id', '=', 'employees.id')
            ->select('departments.*', DB::raw("MAX(employees.salary) AS max_price"), DB::raw("COUNT(department_employee.employee_id) AS employee_count"))
            ->groupBy('departments.id')
            ->get();

        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        return DepartmentResource::make(Department::create([
            'department_name' => $request->department_name,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Department::findOrFail($id);
        return DepartmentResource::make($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, $id)
    {
        $department = Department::findOrFail($id)
             ->update([
            'department_name' => $request->department_name,
        ]);
        return DepartmentResource::collection($department);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return DepartmentResource::make(Department::findOrFail($id)->destroy());
    }
}
