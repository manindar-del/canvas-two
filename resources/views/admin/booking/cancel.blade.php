@extends('layouts.admin')

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
        <div class="col-md-6">
             <a class="btn btn-info" href="{{ route('admin.booking.index') }}">Back to All Booking</a>
        </div>
        <div class="col-md-6">
        <form action="{{ route('bookingadmin.cancel.update', [$booking->id, $booking_id]) }}" method="POST">
            <div class="text-right">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-lg btn-primary">Confirm Booking Cancellation</button>
            </div>
        </form>
        </div>
        @foreach ($booking->data as $_booking)
            @if (!empty($_booking->BookingDetails->BookingId) && $_booking->BookingDetails->BookingId == $booking_id)
                @include('admin.booking._booking-data')
            @endif
        @endforeach

    </div>
</div>
@endsection
