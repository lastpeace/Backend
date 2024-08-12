<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
        $success['username'] = $user->username;
        $success['phone'] = $user->phone;
        $success['email'] = $user->email;

        return response()->json([
            'success' => true,
            'message' => 'Sukses register',
            'data' => $success
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan',
                'data' => $validator->errors()
            ]);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['username'] = $auth->username;
            $success['phone'] = $auth->phone;
            $success['email'] = $auth->email;

            return response()->json([
                'success' => true,
                'message' => 'Login sukses',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek username dan password lagi',
                'data' => null
            ]);
        }
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

    $employee->update($request->only(['image', 'name', 'phone', 'divisions_id', 'position']));

    return response()->json([
        'status' => 'success',
        'message' => 'Employee updated successfully',
        'data' => [
            'employee' => $employee
        ]
    ], 200);
}

//menghapus token dengan logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
            'data' => null
        ], 200);
    }
}