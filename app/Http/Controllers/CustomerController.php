<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Customer::all();
        if (!$data) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Customers not found',
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Successfully retrieved data',
                    'data' => $data,
                ]
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required|string',
        ]);
    
        DB::connection('mysql')->table('customers')->insert([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        return response()->json(['success' => true, 'message' => 'Customer created successfully mantap'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = DB::connection('mysql')->table('customers')->where('id', $id)->first();
    
        if (is_null($customer)) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Customer found',
            'customer' => $customer
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

    $this->validate($request, [
        'full_name' => 'sometimes|required|string',
        'username' => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:customers,email,'.$id,
        'phone_number' => 'sometimes|required|string',
    ]);

    $customer = DB::connection('mysql')->table('customers')->where('id', $id)->first();
    
    if (is_null($customer)) {
        return response()->json([
            'success' => false,
            'message' => 'Customer not found',
        ], 404);
    }

    $updateData = [
        'full_name' => $request->input('full_name', $customer->full_name),
        'username' => $request->input('username', $customer->username),
        'email' => $request->input('email', $customer->email),
        'phone_number' => $request->input('phone_number', $customer->phone_number),
        'updated_at' => Carbon::now()
    ];

    DB::connection('mysql')->table('customers')->where('id', $id)->update($updateData);
    $updatedCustomer = DB::connection('mysql')->table('customers')->where('id', $id)->first();

    return response()->json([
        'success' => true,
        'message' => 'Customer updated successfully',
        'data' => $updatedCustomer
    ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customers = DB::connection('mysql')->table('customers')->where('id', $id)->first();
        if (is_null($customers)) {
            return response()->json([
                'success' => false,
                'message' => 'customers Not Found',
            ], 404);
        }
    
        DB::connection('mysql')->table('customers')->where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'customers Deleted',
        ], 200);
    }
}
