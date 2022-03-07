<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\ShippingAddressRequest;
use App\Order;
use App\OrderDetail;
use App\SecondHandAccount;
use App\ShippingAddress;
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
            return view('public.checkout-page');
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

            if ($request->payment_method == 'card') {
                Stripe::setApiKey('sk_test_51KSNTBBsBdaI2A0Y0Yg7T00pxWqmQXGihqjLuyESlaMGRZAAW64rrgeRmBu7r3n71wFw3DC0gfkNwiaoP3aRc8Dx00wzjf5GFv');
                $token = $request->stripeToken;
                $charge = Charge::create([
                    'amount' => $total * 100,
                    'currency' => 'BDT',
                    'description' => 'Book payments',
                    'source' => $token,
                ]);
            }




            $shipping_address = ShippingAddress::where('user_id', $user->id)->latest()->first();

            $order->user_id = $user->id;
            $order->shipping_id = $shipping_address->id;
            $order->total_price = $total;
            $order->payment_type = $request->payment_method;

            $order->save();

            $order_id = $order->id;

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
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;

        $shipping = ShippingAddress::create($input);

        return redirect()->route('cart.payment');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('public.payment');
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
