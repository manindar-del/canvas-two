@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @foreach ($booking->data as $_booking)
            @php
                // dd($booking->data);
            @endphp
            @include('agent.booking._booking-data')
            {{-- @if (!empty($_booking->BookingDetails->BookingId))
            @else
            @endif --}}
        @endforeach

        @if(!empty($booking->tour_data))
            @foreach ($booking->tour_data as $_booking)
                @php
                    //dd($booking->tour_data);
                @endphp
                @include('agent.booking._booking-tour-data')
            @endforeach
        @endif

        @if(!empty($booking->transfer_data))
            @foreach ($booking->transfer_data as $_booking)
                @php
                    //dd($booking->transfer_data);
                @endphp
                @include('agent.booking._booking-transfer-data')
            @endforeach
        @endif

    </div>
</div>
@endsection
