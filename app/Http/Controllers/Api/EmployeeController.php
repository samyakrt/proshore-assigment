<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\Api\V1\EmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $key = $request->get('key');
        $order = $request->get('order');

        $search_fields = [
            'employees.name',
            'address',
            'departments.name',
        ];

        $employees = Employee::query();
        $employees->join('departments', 'departments.id', 'employees.department_id');

        $employees->select('employees.*', 'departments.name as department_name');

        if (!empty($order)) {
            if (in_array($key, ['name', 'address'])) {
                $employees->orderBy($key, $order);
            }

            if ($key === 'department') {
                $employees->orderBy('department_name', $order);
            }
        }


        $employees->when($request->has('search'), function ($query) use ($request,$search_fields) {
            foreach($search_fields as $field) {
                $query->orWhere($field, 'like', '%' . $request->get('search') . '%');
            }
        });
        $employees->when($request->has('manager_id'), fn () => $employees->where([['manager_id', $request->get('manager_id')], ['employees.id', '!=', $request->get('manager_id')]]));

        $employees = $employees->paginate($perPage, ['*'], 'page', $page);
        return response()->json($employees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        try {
            $input = $request->validated();
            Employee::create($input);
            return response()->created([
                'message' => 'Employee Created Successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->serverError([
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $employee = new EmployeeResource($employee->load('department', 'role'));

        return response()->json(compact('employee'));
    }



    public function showEmployees(Employee $employee)
    {
        $employees = [];
        if ($employee->role_id === config('role.admin')) {
            $employees = Employee::whereManagerId($employee->manager_id)->paginate();
        }
        return response()->json(compact('employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        try {
            $input = $request->validated();
            $employee->update($input);
            return response()->json([
                'message' => 'Employee Updated Successfully.',
            ]);
        } catch (\Throwable $th) {
            return response()->serverError([
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json([
            'message' => 'Employee Deleted Successfully.',
        ]);
    }

    public function managers(Request $request)
    {
        logger('123');
        $department_id = $request->get('department_id') ?? null;

        $managers = Employee::where([
            'department_id' => $department_id,
            'role_id' => config('role.admin'),
        ])->select('id', 'name')->get();

        return response()->json(compact('managers'));
    }
}
