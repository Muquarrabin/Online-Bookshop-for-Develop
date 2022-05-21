<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\ShippingAddressRequest;
use App\Order;
use App\OrderDetail;
use App\SecondHandAccount;
use App\ShippingAddress;
use App\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Stripe;
use Cart;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cart::content()->count()) {
            $shipping_areas=ShippingCharge::all();
            return view('public.checkout-page',compact('shipping_areas'));
        }
        abort(403, 'Cart is empty! you can not checkout');
    }

    public function pay(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = new Order();
            $user = Auth::user();

            $total = $request->cart_total;



            $shipping_address = ShippingAddress::where('user_id', $user->id)->latest()->first();

            $order->user_id = $user->id;
            $order->shipping_id = $shipping_address->id;
            $order->shipping_charge_id = $request->shipping_charge_id;
            $order->shipping_charge = $request->shipping_charge;
            $order->total_price = $total;
            $order->payment_type = $request->payment_method;

            $order->save();

            $order_id = $order->id;
            if ($request->payment_method == 'card') {
                Stripe::setApiKey('sk_test_51KjSKLDWKa969w29aCGH6ooOJADL3WhtF3bzS8U7Ywygl7jZlWmjdG8li3CxBU4s5dSZSuwJ6hF5fGIpRuSzElUx00xBqFSO1N');
                $token = $request->stripeToken;
                $charge = Charge::create([
                    'amount' => $total * 100,
                    'currency' => 'BDT',
                    'description' => 'Book payments of ' . $order_id,
                    'source' => $token,
                ]);
            }
            foreach (Cart::content() as $cartItem) {
                $orderDetails = new OrderDetail();

                $orderDetails->order_id = $order_id;
                $orderDetails->book_id = $cartItem->id;
                $orderDetails->book_name = $cartItem->name;
                $orderDetails->price = $cartItem->price;
                $orderDetails->book_quantity = $cartItem->qty;

                $orderDetails->save();

                Cart::remove($cartItem->rowId);

                $remove_product = Book::findOrFail($orderDetails->book_id);

                $remove_product->update([
                    'quantity' => $remove_product->quantity - $orderDetails->book_quantity,
                ]);

            }
            DB::commit();
            return redirect()->route('user.orders')
                ->with('success_message', 'Order placed successfully. Wait for confirmation.');
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
            return redirect()->route('user.orders')
                ->with('alert_message', 'Error:' . $th->getMessage());
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
    public function store(ShippingAddressRequest $request)
    {
        $shipping_address = new ShippingAddress();
        $shipping_address->user_id = Auth::user()->id;
        $shipping_address->shipping_name = $request->shipping_name;
        $shipping_address->mobile_no = $request->mobile_no;
        $shipping_address->address = $request->address;
        $shipping_address->save();

        return redirect()->route('cart.payment',['area_id'=>$request->area_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $shipping_area=ShippingCharge::findOrFail($request->area_id);
        return view('public.payment',compact('shipping_area'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
