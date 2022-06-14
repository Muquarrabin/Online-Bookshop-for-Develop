@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="payment-area">
            <h4 class="my-4 bg-dark p-3 text-white">Make your payment</h4>

            <div class="cart-summary my-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Order summary</h4>
                    </div>
                    <div class="card-body">
                        <p>Total products = {{Cart::content()->count()}}</p>
                        <p>Product Cost = {{Cart::total()}} TK</p>
                        <p>Shipping cost = {{ $shipping_area->amount }} TK</p>
                        <p><strong>Total cost = {{Cart::total()+$shipping_area->amount}} TK</strong></p>
                    </div>
                </div>
            </div>
            <div class="bg-light p-3 my-4">
                <div class="row">
                    <div class="col">
                        <form action="{{route('cart.checkout')}}" method="post">
                            @csrf
                            <input type="hidden" name="shipping_charge_id" value="{{ $shipping_area->id }}">
                            <input type="hidden" name="shipping_charge" value="{{ $shipping_area->amount }}">
                            <input type="hidden" name="cart_total" value="{{Cart::total()+$shipping_area->amount}}">
                            <input type="hidden" name="payment_method" value="cash">
                            <button type="submit" class="btn btn-success btn-sm"><strong>Cash on delivery</strong></button>
                        </form>
                    </div>
                    <div class="col">
                        <form action="{{route('cart.checkout')}}" method="post">
                            @csrf
                            <input type="hidden" id="customer_name" name="customer_name" value="{{ $user->name }}">
                            <input type="hidden" id="email" name="email" value="{{ $user->email }}">
                            <input type="hidden" id="mobile" name="mobile" value="{{ $shipping_address->mobile }}">
                            <input type="hidden" id="address" name="address" value="{{ $shipping_address->address }}">
                            <input type="hidden" id="shipping_charge_id" name="shipping_charge_id" value="{{ $shipping_area->id }}">
                            <input type="hidden" id="shipping_charge" name="shipping_charge" value="{{ $shipping_area->amount }}">
                            <input type="hidden" id="cart_total" name="cart_total" value="{{Cart::total()+$shipping_area->amount}}">
                            <input type="hidden" id="payment_method" name="payment_method" value="local-card">
                            <button type="submit" class="btn btn-info btn-sm"><strong>Pay via SSLCOMMERZ</strong></button>
                        </form>
                    </div>
                    <div class="col">
                        <form action="{{route('cart.checkout')}}" method="post">
                            @csrf
                            <input type="hidden" name="shipping_charge_id" value="{{ $shipping_area->id }}">
                            <input type="hidden" name="shipping_charge" value="{{ $shipping_area->amount }}">
                            <input type="hidden" name="cart_total" value="{{Cart::total()+$shipping_area->amount}}">
                            <input type="hidden" name="payment_method" value="stripe-card">
                            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="pk_test_51KjSKLDWKa969w29zJXpuiCqkuNIGY0hs7Db1jWN42MBGbCUHigWlk6rgVBenuhf51Il6g1cu1Li5MTa9HdTMjSw00K4Yzk5qD"
                                    data-label="Pay with Stripe"
                                    data-amount=""
                                    data-name="CTG Book Shop"
                                    data-description="CTG Book Shop payment"
                            data-locale="auto">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
