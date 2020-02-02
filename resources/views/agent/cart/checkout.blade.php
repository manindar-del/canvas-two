@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @if (empty($cart) && empty($tour_cart) && empty($transfer_cart))
            <div class="alert alert-danger">
                <h4>Your cart is empty</h4>
            </div>
        @endif

        <form class="_checkout" action="{{ route('paywithpaypal') }}" method="POST" id="checkoutForm">


            @include('agent.cart._checkout-hotel')

            @include('agent.cart._checkout-tour')

            @include('agent.cart._checkout-transfer')

            @if (!empty($cart) || (!empty($tour_cart)) || !empty($transfer_cart))
                <p>
                    <input type="checkbox" required>
                    By proceeding with the Booking I acknowledge that I have read and accepted the General Booking Conditions and Policies.
                </p>
            @endif

            <div class="_checkout__action">
                {{ csrf_field() }}
                {{-- <input type="hidden" value="" name="payment_type" id="pay_later"> --}}
                <a href="{{ route('home') }}" class="btn btn-primary">Add Other Service</a>
                @if (!empty($cart) || !empty($tour_cart) || !empty($transfer_cart))
                    <button type="submit" class="btn btn-primary _checkout__reviewBtn" id="reviewBtn">Continue Booking</button>
                    <button type="submit" class="btn btn-primary _checkout__editBtn" id="editBtn">Edit Detail</button>
                    {{-- <button type="submit" class="btn btn-primary _checkout__bookBtn bookBtn" id="payLaterBtn">Pay Later</button> --}}
                    <input type="submit" class="btn btn-primary _checkout__bookBtn bookBtn" name="pay_walltet" id="bookBtn" value="Pay Using Credit Balance" />
                    <input type="submit" class="btn btn-primary _checkout__bookBtn bookBtn" name="pay_online" id="payWalletBtn" value="Pay Online" />
                @endif
            </div>

        </form>

    </div>
</div>
@endsection

@push('footer-bottom')
    <script>
        var $checkoutForm = jQuery('#checkoutForm');
        var review = false,
            edit = false;
        jQuery(document).on('click', '#reviewBtn', function(e) {
            // e.preventDefault();
            // $checkoutForm.addClass('_review');
            review = true;
            edit = false;
        });
        jQuery(document).on('click', '#editBtn', function(e) {
            edit = true;
            review = false;
            $checkoutForm.find('input').removeAttr('readonly');
        });
        jQuery(document).on('click', '.bookBtn', function(e) {
            edit = false;
            review = false;
            $('#pay_later').val("Balance");
        });
        jQuery(document).on('click', '#payLaterBtn', function(e) {
            edit = false;
            review = false;
            $('#pay_later').val("Later");
        });
        jQuery(document).on('submit', $checkoutForm, function(e) {
            if (review) {
                e.preventDefault();
                $checkoutForm.addClass('_review')
                $checkoutForm.find('input').attr('readonly', 'readonly');
                jQuery('html, body').animate({
                    scrollTop: 0,
                }, 500);
            }
            if (edit) {
                e.preventDefault();
                $checkoutForm.removeClass('_review');
                $checkoutForm.find('input').removeAttr('disabled');
            }
        });
    </script>
@endpush