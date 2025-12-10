<?php 

namespace App\Repositories\Customer;

use App\Models\Customer;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getCustomers(Request $request)
    {
        DB::beginTransaction();
        try{
            $customers = Customer::where('is_verified',1)->paginate(config('app.per_page'));
            return response()->json(
                [
                    'success' => true,
                    'data' => $customers,
                ]
            ,200);
        }catch(\Exception $e)
        {
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
        
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('customers','public');
            $url = asset('storage/'.$path);
            
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
        
    } catch(\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Registration failed: ' . $e->getMessage()
        ], 500);
    }
}
}
