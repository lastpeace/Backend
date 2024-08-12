<?php

// app/Http/Controllers/API/EmployeeController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $query = Employee::query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('division_id')) {
            $query->where('division_id', $request->divisions_id);
        }
        $employees = $query->with('division')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diambil',
            'data' => [
                'employees' => $employees->items(),
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                    'last_page' => $employees->lastPage(),
                ],
            ],
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|url',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'divisions_id' => 'required|exists:divisions,id',
            'position' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 400);
        }

        $employee = Employee::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
            'data' => [
                'employee' => $employee
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|url',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'divisions_id' => 'required|exists:divisions,id',
            'position' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'data' => $validator->errors()
            ], 400);
        }

        $employee->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Employee updated successfully',
            'data' => [
                'employee' => $employee
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
                'data' => null
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Employee deleted successfully',
            'data' => null
        ], 200);
    }
}
