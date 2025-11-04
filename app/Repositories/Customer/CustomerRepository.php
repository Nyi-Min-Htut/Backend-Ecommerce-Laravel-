<?php 

namespace App\Repositories\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try{
            $customer = Customer::create($request->all());
        if($customer)
        {
            if($request->hasFile('image'))
            {
                $path = $request->file('image')->store('customers','public');
                $url = asset('storage/'.$path);
            }

            $customer->image_url = $url;
            $customer->image_path = $path;
            $customer->save();
        }
        DB::commit();
        }catch(\Exception $e)
        {
            DB::rollBack();
            throw $e->getMessage();
        }
    }
}
