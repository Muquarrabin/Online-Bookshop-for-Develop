<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\ShippingAddressRequest;
use App\Library\SslCommerz\SslCommerzNotification;
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
            if ($request->payment_method == 'stripe-card') {
                Stripe::setApiKey('sk_test_51KjSKLDWKa969w29aCGH6ooOJADL3WhtF3bzS8U7Ywygl7jZlWmjdG8li3CxBU4s5dSZSuwJ6hF5fGIpRuSzElUx00xBqFSO1N');
                $token = $request->stripeToken;
                $charge = Charge::create([
                    'amount' => $total * 100,
                    'currency' => 'BDT',
                    'description' => 'Book payments of ' . $order_id,
                    'source' => $token,
                ]);
            }
            if ($request->payment_method=='local-card'){
                # Here you have to receive all the order data to initate the payment.
                # Lets your oder trnsaction informations are saving in a table called "orders"
                # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

                $post_data = array();
                $post_data['total_amount'] = $order->total_price; # You cant not pay less than 10
                $post_data['currency'] = "BDT";
                $post_data['tran_id'] = $order->id; // tran_id must be unique

                # CUSTOMER INFORMATION
                $post_data['cus_name'] = $user->name;
                $post_data['cus_email'] = $user->email;
                $post_data['cus_add1'] = $shipping_address->address;
                $post_data['cus_add2'] = "";
                $post_data['cus_city'] = "";
                $post_data['cus_state'] = "";
                $post_data['cus_postcode'] = "";
                $post_data['cus_country'] = "Bangladesh";
                $post_data['cus_phone'] = $shipping_address->mobile_no;
                $post_data['cus_fax'] = "";
                # SHIPMENT INFORMATION
                $post_data['ship_name'] = $shipping_address->shipping_name;
                $post_data['ship_add1'] = $shipping_address->address;
                $post_data['ship_add2'] = $shipping_address->address;
                $post_data['ship_city'] = $shipping_address->city;
                $post_data['ship_state'] = $shipping_address->city;
                $post_data['ship_postcode'] = $shipping_address->post_code;
                $post_data['ship_phone'] = "";
                $post_data['ship_country'] = "Bangladesh";

                $post_data['shipping_method'] = "NO";
                $post_data['product_name'] = "Books";
                $post_data['product_category'] = "Books";
                $post_data['product_profile'] = "physical-goods";

                DB::commit();

                $sslc = new SslCommerzNotification();

                # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
                $payment_options = $sslc->makePayment($post_data, 'hosted');
//                dd($payment_options);
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
            $order->order_status=0;
            $order->save();
            DB::commit();
            return redirect()->route('user.orders')
                ->with('success_message', 'Order placed successfully. Wait for confirmation.');
        } catch (\Throwable $th) {
            DB::rollBack();
//            throw $th;
            return redirect()->route('user.orders')
                ->with('alert_message', 'Error:' . $th->getMessage());
        }
    }

    public function sslCommerzSuccess(Request $request)
    {
        DB::beginTransaction();
        try {
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');

            $sslc = new SslCommerzNotification();

            #Check order status in order tabel against the transaction id or order id.
            $order_details = Order::find($tran_id);
            if ($order_details->order_status == 0) {
                $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

                if ($validation == TRUE) {
                    /*
                    That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successfull transaction to customer
                    */

                    $order_details->order_status=1;
                    $order_details->save();
                    foreach (Cart::content() as $cartItem) {
                        $orderDetails = new OrderDetail();

                        $orderDetails->order_id = $order_details->id;
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
                } else {
                    /*
                    That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $order_details->delete();
                    DB::commit();
                    return redirect()->route('user.orders')
                        ->with('alert_message', 'Payment Failed. Please Re-Order!');

                }
            } else if ($order_details->status == 1) {
                /*
                 That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
                 */
                DB::commit();
                return redirect()->route('user.orders')
                    ->with('success_message', 'Order placed successfully. Wait for confirmation.');
            } else {
                #That means something wrong happened. You can redirect customer to your product page.
                $order_details->delete();
                DB::commit();
                return redirect()->route('user.orders')
                    ->with('alert_message', 'Payment Failed. Please Re-Order!');
            }
        }
        catch (\Throwable $th){
            DB::rollBack();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Something went wrong! | ' .$th->getMessage() );
        }

    }

    public function sslCommerzFail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = Order::find($tran_id);

        if ($order_details->order_status == 0) {
            $order_details->delete();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Payment Failed. Please Re-Order!');
        } else if ($order_details->order_status == 1 ) {
            return redirect()->route('user.orders')
                ->with('success_message', 'Order already placed successfully. Wait for confirmation.');
        } else {
            $order_details->delete();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Payment Failed. Please Re-Order!');
        }

    }

    public function sslCommerzCancel(Request $request)
    {
        $tran_id = $request->input('tran_id');


        $order_details = Order::find($tran_id);

        if ($order_details->order_status == 0) {
            $order_details->delete();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Order Cancelled!');
        } else if ($order_details->order_status == 1 ) {
            return redirect()->route('user.orders')
                ->with('success_message', 'Order already placed successfully. Wait for confirmation.');
        } else {
            $order_details->delete();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Payment Failed. Please Re-Order!');
        }
    }

    public function sslCommerzIpn(Request $request)
    {
        DB::beginTransaction();
        try {
            #Received all the payement information from the gateway
            if ($request->input('tran_id')) #Check transation id is posted or not.
            {

                $tran_id = $request->input('tran_id');

                #Check order status in order tabel against the transaction id or order id.
                $order_details = Order::find($tran_id);

                if ($order_details->order_status == 0) {
                    $sslc = new SslCommerzNotification();
                    $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                    if ($validation == TRUE) {
                        /*
                        That means IPN worked. Here you need to update order status
                        in order table as Processing or Complete.
                        Here you can also sent sms or email for successful transaction to customer
                        */
                        $order_details->order_status=1;
                        $order_details->save();
                        foreach (Cart::content() as $cartItem) {
                            $orderDetails = new OrderDetail();

                            $orderDetails->order_id = $order_details->id;
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
                    } else {
                        /*
                        That means IPN worked, but Transation validation failed.
                        Here you need to update order status as Failed in order table.
                        */
                        $order_details->delete();
                        DB::commit();
                        return redirect()->route('user.orders')
                            ->with('alert_message', 'Payment failed!');
                    }

                }  else if ($order_details->order_status == 1 ) {
                    DB::commit();
                    return redirect()->route('user.orders')
                        ->with('success_message', 'Order already placed successfully. Wait for confirmation.');
                } else {
                    $order_details->delete();
                    DB::commit();
                    return redirect()->route('user.orders')
                        ->with('alert_message', 'Payment Failed. Please Re-Order!');
                }
            } else {
                return redirect()->route('user.orders')
                    ->with('alert_message', 'Invalid Data!');
            }
        }
        catch (\Throwable $th){
            DB::rollBack();
            return redirect()->route('user.orders')
                ->with('alert_message', 'Something went wrong! | ' .$th->getMessage() );
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
        $shipping_address->city = $request->city;
        $shipping_address->post_code = $request->post_code;
        $shipping_address->save();

        return redirect()->route('cart.payment',['area_id'=>$request->area_id, 'address_id' => $shipping_address->id]);
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
        $shipping_address=ShippingAddress::findOrFail($request->address_id);
        $user=Auth::user();
        return view('public.payment',compact('shipping_area','user','shipping_address'));
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
