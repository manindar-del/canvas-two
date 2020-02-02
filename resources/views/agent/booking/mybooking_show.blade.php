@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @if(!empty($booking->tour_data))
            @foreach ($booking->tour_data as $_booking)
                @php
                //$route = Route::currentRouteName();
                @endphp
                @if($_booking->BookingId == $booking_id)
                    @include('agent.booking._booking-tour-data')
                @endif
            @endforeach
        @endif

        @if(!empty($booking->transfer_data))
            @foreach ($booking->transfer_data as $_booking)
                @php
                //$route = Route::currentRouteName();
                @endphp
                @if($_booking->BookingId == $booking_id)
                    @include('agent.booking._booking-transfer-data')
                @endif
            @endforeach
        @endif

    </div>
</div>
@endsection
