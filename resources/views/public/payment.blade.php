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
                <form action="{{route('cart.checkout')}}" method="post">
                    @csrf
                    <input type="hidden" name="shipping_charge_id" value="{{ $shipping_area->id }}">
                    <input type="hidden" name="shipping_charge" value="{{ $shipping_area->amount }}">
                    <input type="hidden" name="cart_total" value="{{Cart::total()+$shipping_area->amount}}">
                    <input type="hidden" name="payment_method" value="cash">
                    <button type="submit" class="btn btn-success btn-sm"><strong>Cash on delivery</strong></button>
                </form>
            </div>
            <div class="bg-light p-3 my-4">
                <form action="{{route('cart.checkout')}}" method="post">
                    @csrf
                    <input type="hidden" name="shipping_charge_id" value="{{ $shipping_area->id }}">
                    <input type="hidden" name="shipping_charge" value="{{ $shipping_area->amount }}">
                    <input type="hidden" name="cart_total" value="{{Cart::total()+$shipping_area->amount}}">
                    <input type="hidden" name="payment_method" value="card">
                    <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="pk_test_51KjSKLDWKa969w29zJXpuiCqkuNIGY0hs7Db1jWN42MBGbCUHigWlk6rgVBenuhf51Il6g1cu1Li5MTa9HdTMjSw00K4Yzk5qD"
                            data-amount=""
                            data-name="CTG Book Shop"
                            data-description="CTG Book Shop payment"
                            data-locale="auto">
                    </script>
                </form>
            </div>

        </div>
    </div>
@endsection
