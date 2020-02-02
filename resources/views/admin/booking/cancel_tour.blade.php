@extends('layouts.admin')

@section('content')

    @php

        $tour_date = $booking_data->form->form->tour_date;
        $booking_date =  date('Y-m-d',strtotime($tour_date));
        $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($tour_date)));

        $adult = $booking_data->form->form->adult;
        $child = $booking_data->form->form->child;
        $infant = $booking_data->form->form->infant;

        $adult_price = $booking_data->tour->adult_price;
        $child_price = $booking_data->tour->child_price;
        $infant_price = $booking_data->tour->infant_price;

        $total_adult_amount = $adult * $adult_price;
        $total_child_amount = $child * $child_price;
        $total_infant_amount = $infant * $infant_price;

        $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);

    @endphp

    <div class="_page">
        <div class="container">

            @include('partials._info')
            @include('partials._errors')

            <form action="{{ route('tourbookingadmin.cancel.update', [$booking->id, $booking_id]) }}" method="POST">
                <div class="text-center">
                    <h4 class="text-danger">
                        <span>Canellation Charges</span>
                        @if (date('Y-m-d') <= $cancellation_last_day)
                            <strong>&#8377;0</strong>
                        @else
                            <strong>&#8377;{{ $total_tour_rate }}</strong>
                        @endif
                    </h4>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-lg btn-info">Confirm Cancellation</button>
                </div>
            </form>

            <hr>

            @foreach ($booking->tour_data as $_booking)
                @if($_booking->BookingId == $booking_id)
                    @include('admin.booking._booking-tour-data')
                @endif
            @endforeach

        </div>
    </div>

@endsection
