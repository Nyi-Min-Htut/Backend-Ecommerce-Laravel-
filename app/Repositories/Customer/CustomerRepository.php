<?php

namespace App\Repositories\Customer;

use App\Models\Customer;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getCustomers(Request $request)
    {
        DB::beginTransaction();
        try {
            $customers = Customer::where('is_verified', 1)->paginate(config('app.per_page'));
            return response()->json(
                [
                    'success' => true,
                    'data' => $customers,
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e->getMessage();
        }
    }
    public function registerCustomer(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate request first


            // Prepare customer data - convert booleans properly
            $customerData = [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'password' => $request->password,
                'address' => $request->address,
                'gender' => $request->gender,
                'remark' => $request->remark,
                'nrc' => $request->nrc,
                'is_verified' => 0, // Use integer 0/1 instead of boolean false/true
                'is_ban' => 0,
            ];

            $customer = Customer::create($customerData);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('customers', 'public');
                $url = asset('storage/' . $path);

                $customer->image_url = $url;
                $customer->image_path = $path;
                $customer->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => $customer
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProfile()
    {
        try {
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $customer
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
                'phone_number' => 'sometimes|string|max:20',
                'date_of_birth' => 'sometimes|date',
                'address' => 'sometimes|string',
                'gender' => 'sometimes|in:male,female,other',
                'remark' => 'sometimes|string',
                'nrc' => 'sometimes|string|max:50',
                'password' => 'sometimes|string|min:6',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($customer->image_path && Storage::exists($customer->image_path)) {
                    Storage::delete($customer->image_path);
                }

                $path = $request->file('image')->store('customers', 'public');
                $url = asset('storage/' . $path);

                $validatedData['image_url'] = $url;
                $validatedData['image_path'] = $path;
            }

            // Handle password update
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Update customer
            $customer->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $customer->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
    public function favProductsByCustomer()
    {
        $customer = Auth::guard('customer')->user();
        $favProducts = $customer->favProducts()
            ->with(['productImages' => function ($query) {
                // Only load images where product_variant_id is null
                $query->whereNull('product_variant_id');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favProducts,
        ], 200);
    }

    public function saveProductsByCustomer()
    {
        $customer = Auth::guard('customer')->user();
        $saveProducts = $customer->saveProducts()
            ->with(['productImages' => function ($query) {
                // Only load images where product_variant_id is null
                $query->whereNull('product_variant_id');
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $saveProducts,
        ], 200);
    }
}
