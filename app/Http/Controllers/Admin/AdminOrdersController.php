<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Book;
use App\SecondHandAccount;
use Illuminate\Support\Facades\DB;

class AdminOrdersController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.orders.all-orders', compact('orders'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $order_details = OrderDetail::where('order_id', $id)->get();

        return view('admin.orders.order-details', compact('order_details', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        DB::beginTransaction();
        try {
            $input = $request->all();
            $order = Order::with('orderDetail','second_hand_acc')->findOrFail($id);
            $order->update($input);
            foreach ($order->orderDetail as $item) {
                $book = Book::with('selling_requests')->findOrFail($item->book_id);
                if ($order->order_status) {
                    if ($book->is_second_hand) {
                        $sec_hand_acc = new SecondHandAccount();
                        $sec_hand_acc->selling_request_id = $book->selling_request_id;
                        $sec_hand_acc->order_id = $order->id;
                        $sec_hand_acc->asking_price = $book->selling_requests->asking_price;
                        $sec_hand_acc->selling_price = $book->selling_requests->selling_price;
                        if ($book->selling_requests->selling_price < $order->total_price) {
                            $sec_hand_acc->discount = $order->total_price - $book->selling_requests->selling_price;
                        }
                        $sec_hand_acc->commission_earned = $order->total_price - $book->selling_requests->asking_price;
                        $sec_hand_acc->save();
                    }
                }
                else{
                    $order->second_hand_acc->delete();
                }
            }


            DB::commit();
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('alert_message', 'Order deleted successfully');
    }
}
