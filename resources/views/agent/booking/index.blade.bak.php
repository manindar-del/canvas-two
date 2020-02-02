@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @if ($all_booking->count())

            @foreach ($all_booking as $booking)

                @php
                    // dd($booking->data)
                @endphp

                @foreach ($booking->data as $_booking)

                    @php
                        // dd($_booking)
                    @endphp

                    <div class="_booking__item">

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="_booking__item__title {{ 'Confirmed' == $_booking->BookingDetails->BookingStatus ? 'text-success' : 'text-danger' }}">
                                    {{ $_booking->BookingDetails->BookingStatus }}
                                    :
                                    {{ $_booking->BookingRequest->Booking->Name }}
                                </div>
                            </div>
                            <div class="col-xs-12">
                                @if (!empty($_booking->BookingDetails->BookingReason))
                                    <strong class="text-danger">{{ $_booking->BookingDetails->BookingReason }}</strong>
                                @else
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-3">
                                            <strong>Booking Id:</strong>
                                            <span>{{ $_booking->BookingDetails->BookingId }}</span>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <strong>Booking Code</strong>
                                            <span>{{ $_booking->BookingDetails->BookingCode }}</span>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <strong>Status</strong>
                                            <span>{{ $_booking->BookingDetails->BookingStatus }}</span>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                            <strong>Price</strong>
                                            <span>{{ $_booking->BookingDetails->BookingCurrency }} {{ ceil($_booking->BookingDetails->BookingPrice) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <strong>Check In:</strong>
                                <span>{{ $_booking->BookingRequest->Booking->ArrivalDate }}</span>
                            </div>
                            <div class="col-sm-3">
                                <strong>Check Out:</strong>
                                <span>{{ $_booking->BookingRequest->Booking->DepartureDate }}</span>
                            </div>
                        </div>

                        @foreach ($_booking->BookingRequest->Booking->RoomDetails as $_RoomDetail)

                            @php
                                $adults = explode('|', $_RoomDetail->Adults);
                                $children = explode('|', $_RoomDetail->Children);
                                $types = explode('|', $_RoomDetail->Type);
                                $rates = explode('|', $_RoomDetail->TotalRate);
                            @endphp

                            @for ($i = 0; $i < $_RoomDetail->TotalRooms; $i++)
                                <div class="row">
                                    <div class="col-sm-3">
                                        <strong>Room #{{ $i + 1 }}:</strong>
                                        <span>{{ $types[$i] }}</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Rate:</strong>
                                        <span>{{ ceil($rates[$i]) }}</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Adults:</strong>
                                        <span>{{ $adults[$i] }}</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <strong>Children:</strong>
                                        <span>{{ $children[$i] }}</span>
                                    </div>
                                </div>
                            @endfor

                            @if (empty($cancelllations[$_booking->BookingDetails->BookingId]))
                                @foreach ($_RoomDetail->Guests as $_guest)
                                    @php
                                        if (!is_array($_guest)) {
                                            $_guest = [$_guest];
                                        }
                                    @endphp
                                    @foreach ($_guest as $_guest_data)
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <strong>Salutation:</strong>
                                                <span>{{ $_guest_data->Salutation }}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <strong>First Name:</strong>
                                                <span>{{ $_guest_data->FirstName }}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <strong>Last Name:</strong>
                                                <span>{{ $_guest_data->LastName }}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <strong>Age:</strong>
                                                <span>{{ $_guest_data->Age }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach

                        @if (!empty($cancelllations[$_booking->BookingDetails->BookingId]))
                            @php
                                $_cancellation = $cancelllations[$_booking->BookingDetails->BookingId];
                            @endphp
                            <div class="alert alert-danger mt-5" >
                                <p>
                                    <strong>Booking Cancelled</strong>
                                    <br>
                                    <span>Amount Charged:</span>
                                    <strong>{{ $_cancellation->currency }} {{ ceil($_cancellation->charges) }}</strong>
                                </p>
                            </div>
                        @else
                            @if ('Confirmed' == $_booking->BookingDetails->BookingStatus)
                                @if (date('d/m/Y') <= $_booking->BookingRequest->Booking->DepartureDate)
                                    <div class="text-right mt-5">
                                        <a
                                            href="{{ route('booking.cancel.index', [$booking->id, $_booking->BookingDetails->BookingId]) }}"
                                            class="btn btn-primary"
                                        >Cancel Booking</a>
                                    </div>
                                @endif
                            @endif
                        @endif

                    </div>

                @endforeach

            @endforeach

        @else

            <!-- <h2 class="text-center">
                You have not made any bookings yet.
                <br>
                <a href="{{ route('home') }}">
                    <small>Click here to make your first booking</small>
                </a>
            </h2> -->

            <div class="text-center">
                <img src="{{ asset('assets/img/empty-list.png') }}" alt="" />
            </div>

        @endif

    </div>
</div>
@endsection
