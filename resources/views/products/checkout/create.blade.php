@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout Form</h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="row">
            {{-- Left: Billing & Shipping --}}
            <div class="col-md-6">
                <h5>Billing Address</h5>

                <div class="mb-2">
                    <label>Full Name</label>
                    <input class="form-control" value="{{ auth()->user()->name }}" disabled>
                </div>

                <div class="mb-2">
                    <label>Email</label>
                    <input class="form-control" value="{{ auth()->user()->email }}" disabled>
                </div>

                <div class="mb-2">
                    <label>Phone</label>
                    <input name="phone" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>Address</label>
                    <input name="billing_address" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>City</label>
                    <input name="billing_city" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>State</label>
                    <input name="billing_state" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label>Zip</label>
                    <input name="billing_zip" class="form-control" required>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" name="same_as_billing" class="form-check-input" id="sameAsBilling" checked>
                    <label class="form-check-label" for="sameAsBilling">
                        Shipping address same as billing
                    </label>
                </div>

                <div id="shippingFields">
                    <h5>Shipping Address</h5>
                    <div class="mb-2">
                        <label>Address</label>
                        <input name="shipping_address" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>City</label>
                        <input name="shipping_city" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>State</label>
                        <input name="shipping_state" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Zip</label>
                        <input name="shipping_zip" class="form-control">
                    </div>
                </div>
            </div>

            {{-- Right: Payment --}}
            <div class="col-md-6">
                <h5>Payment Method</h5>

                {{-- Payment Method --}}
                <div class="mb-3">
                    <label>Choose Payment Method:</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="card" checked>
                        <label class="form-check-label">Credit / Debit Card</label>
                    </div><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="momo">
                        <label class="form-check-label">Mobile Money</label>
                    </div>
                </div>

                {{-- If Card selected --}}
                <div id="cardFields">
                    <div class="mb-2">
                        <label>Accepted Cards:</label>
                        <div>
                            <img src="/images/mastercard.png" alt="MasterCard" style="height:40px; width:50px"> &nbsp;
                            <img src="/images/visa.png" alt="Visa" style="height:40px; width:50px"> &nbsp;
                            <img src="/images/stanbic.png" alt="Stanbic" style="height:60px; width:50px"> &nbsp;
                            <img src="/images/centenary.png" alt="Centenary" style="height:60px; width:50px"> &nbsp;
                        </div>
                    </div>
                </div>

                {{-- If Mobile Money selected --}}
                <div id="momoFields" style="display: none;">
                    <label>Choose Mobile Network:</label><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="momo_network" value="mtn" checked>
                        <label class="form-check-label">
                            <img src="/images/mtn.png" alt="MTN" style="height:20px;"> MTN Money
                        </label>
                    </div><br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="momo_network" value="airtel">
                        <label class="form-check-label">
                            <img src="/images/airtel.png" alt="Airtel" style="height:20px;"> Airtel Money
                        </label>
                    </div>
                </div>

                <button class="btn btn-success mt-3" type="submit">Proceed to Payment</button>
            </div>
        </div>
    </form>
</div>
@endsection
