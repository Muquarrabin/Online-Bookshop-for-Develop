<?php

namespace App\Http\Controllers\Admin;

use App\ShippingCharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminShippingChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping_charges = ShippingCharge::all();
        return view('admin.shipping_charge.index', compact('shipping_charges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shipping_charge.create');
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
            'area_name' =>'required',
            'amount' =>'required|numeric'
        ]);

        $input = $request->all();
        ShippingCharge::create($input);
        return redirect('/admin/shipping-charges')
            ->with('success_message', 'Shipping charge create successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $charge = ShippingCharge::findOrFail($id);
        return view('admin.shipping_charge.edit', compact('charge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'area_name' =>'required',
            'amount' =>'required|numeric'
        ]);

        $input = $request->all();
        $charge = ShippingCharge::findOrFail($id);
        $charge->update($input);

        return redirect('/admin/shipping-charges')
            ->with('success_message', 'Shipping Charge Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $charge = ShippingCharge::findOrFail($id);
        $charge->delete();
        return redirect()->back()
            ->with('alert_message', 'Shipping Charge deleted successfully');
    }
}
