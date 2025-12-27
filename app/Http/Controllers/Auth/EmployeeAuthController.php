<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeAuthController extends Controller
{
public function login(Request $request)
{
    $request->validate([
        'phone_number' => 'required',
        'password'     => 'required',
    ]);

    $employee = Employee::where('phone_number', $request->phone_number)->first();

    if (!$employee || !Hash::check($request->password, $employee->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    // Optional: single-device login
    $employee->tokens()->delete();

    $token = $employee->createToken('employee_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'token' => $token,
        'token_type' => 'Bearer',
        'employee' =>$employee
    ], 200);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}