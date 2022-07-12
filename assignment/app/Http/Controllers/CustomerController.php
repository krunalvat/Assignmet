<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shops = Shop::select('*')->get();
        $customers = Customer::join('shops','shops.id','customers.shop_id')->select('customers.*','shops.name as shop_name')->get();
        if($request->ajax())
        {
            return DataTables::of($customers)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit">Edit</a>';

                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('customerlist.index',compact('customers','shops'));
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
        if($request->ajax())
        {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required|not_in:0',
                'first_name' => 'required',
                'last_name' => 'required',
                'city' => 'required',
                'images'=> 'required',
                'birthdate' => 'required',
            ]);

            if ($validator->fails()) 
            {
                return response()->json(['errors'=>$validator->getMessageBag()->toArray()]);
            }
            else 
            {
                if($request->hasFile('images'))
                {
                    Customer::updateOrCreate(['id' => $request->id],['shop_id' => $request->shop_id, 'first_name' => $request->first_name,'last_name' => $request->last_name,'avatar' => $request->images ? $request->images->store('upload','public') : null,
                    'city' => $request->city ?? '','birthdate' => date('Y-m-d',strtotime($request->birthdate))
                    ]);
                } 
                else 
                {

                    return response()->json(['data'=>'Image exists']);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customers = Customer::find($id);
        return response()->json($customers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
     
        return response()->json(['success'=>'Shop deleted successfully.']);
    }
}
