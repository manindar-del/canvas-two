@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        <h2>Cancellation Charges</h2>

        <table class="_checkout__table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Charge Type</th>
                    <th>Charge Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $CancellationInformation = $policy->CancellationPolicyResponse->CancellationInformations->CancellationInformation;
                    if (!is_array($CancellationInformation)) {
                        $CancellationInformation = [$CancellationInformation];
                    }
                @endphp
                @foreach ($CancellationInformation as $index => $_info)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{  $_info->StartDate }}</td>
                        <td>{{  $_info->EndDate }}</td>
                        <td>{{  $_info->ChargeType }}</td>
                        <td>{{  $_info->ChargeAmount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('booking.cancel.update', [$booking->id, $booking_id]) }}" method="POST">
            <div class="text-right">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-lg btn-primary">Confirm Booking Cancellation</button>
            </div>
        </form>

        @foreach ($booking->data as $_booking)
            @if ($_booking->BookingDetails->BookingId == $booking_id)
                @include('agent.booking._booking-data')
            @endif
        @endforeach

    </div>
</div>
@endsection
