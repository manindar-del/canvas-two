@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @if (empty($cart) && (empty($tour_cart) && empty($transfer_cart)) )
            <div class="alert alert-danger">
                <h4>Your cart is empty</h4>
            </div>
        @endif

        <div class="_cart">

            {{-- @include('agent.cart._index-hotel') --}}

            @include('agent.cart._index-tour')

            @include('agent.cart._index-transfers')

            @if (!empty($cart) || (!empty($tour_cart)) || !empty($transfer_cart))
                <div class="text-right">
                    {{-- <a class="btn btn-primary" href="{{ route('home') }}">Add More Service</a> --}}
                    <a class="btn btn-primary" href="{{ route('cart.checkout') }}">Proceed Next</a>
                </div>
                <div class="_remarks">
                    <h4 class="_remarks__title">Important Remarks & Policies</h4>
                    <p>
                        <strong>Nationality & Domicile</strong>
                    </p>
                    <p>
                        Passenger travelling to destination where guest is holding a local residency; Booking should be searched with Country of Residence as Nationality in order to avail the valid rates. (i.e. Indian National holding UAE Residence Permit should select Emirati as nationality for search). In case of wrong residency or nationality selected by user at the time of booking; the supplement charges may be applicable and need to be paid directly to the hotel by guest on check in/check out.
                    </p>
                    <p>Additional supplement charges may be charged by the Hotel (which the Guest have to pay directly at the hotel) If the lead guest’s Nationality is different than the Nationality of the other accompanied guests. For more details you can reach out to our operation Team for clarification.</p>
                    <p>
                        <strong>Group Reservation</strong>
                    </p>
                    <p>
                        Our System is a FIT (Free Individual Traveler) Reservation system which allows maximum of 8 Rooms or 48 Passengers to be booked at one time. However any activity which has more than 3 rooms or 9 Passengers booked for the "same date of travel & same hotel of stay" combination is handled as per "group terms & conditions". In such cases we have rights to cancel any confirmed or vouchered reservation(s) without any prior intimation. You are requested to contact our nearest support office in case of group or reservation with more than 3 room requirement.
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection