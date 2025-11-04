<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    //
    public function login(Request $request)
    {

        $customer = Customer::where('phone_number', $request->phone_number)->first();
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Invalid credential'
            ], 401);
        }

        $customer->tokens()->delete();

        $token = $customer->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'Bearer',
            'customer' => $customer
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'message' => 'Logged out successfully'
            ]
        );
    }
}
