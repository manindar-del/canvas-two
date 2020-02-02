@extends('layouts.admin')

@section('content')

    @php

        $transfer_date = $booking_data->form->form->transfer_date;
        $booking_date =  date('Y-m-d',strtotime($transfer_date));
        $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($transfer_date)));

        $adult = $booking_data->form->form->adult;
        $child = $booking_data->form->form->child;

        $adult_price = $booking_data->transfer->adult_price;
        $child_price = $booking_data->transfer->child_price;

        $total_adult_amount = $adult * $adult_price;
        $total_child_amount = $child * $child_price;
        $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);

    @endphp

    <div class="_page">
        <div class="container">

            @include('partials._info')
            @include('partials._errors')

            <form action="{{ route('transferbookingadmin.cancel.update', [$booking->id, $booking_id]) }}" method="POST">
                <div class="text-center">
                    <h4 class="text-danger">
                        <span>Canellation Charges</span>
                        @if (date('Y-m-d') <= $cancellation_last_day)
                            <strong>&#8377;0</strong>
                        @else
                            <strong>&#8377;{{ $total_transfer_rate }}</strong>
                        @endif
                    </h4>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-lg btn-info">Confirm Cancellation</button>
                </div>
            </form>

            <hr>

            @foreach ($booking->transfer_data as $_booking)
                @if($_booking->BookingId == $booking_id)
                    @include('admin.booking._booking-transfer-data')
                @endif
            @endforeach

        </div>
    </div>

@endsection
