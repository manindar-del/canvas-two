@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        <div class="tab-content">
            @if ($all_booking->count())
                <table class="_checkout__table">

                    <thead>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Payment Type</th>
                        <th>Payment Status</th>
                        <th>Details</th>
                    </thead>

                    <tbody>
                        @foreach ($all_booking as $booking)
                            @php
                                //dd($booking->data->BookingDetails);
                                $total_tour_cancell = 0;
                                $total_hotel_cancell = 0;
                                $total_transfer_cancell = 0;

                                foreach ($booking->data as $i => $_booking){
                                    if(!empty($_booking->BookingDetails->BookingId)){
                                        if (!empty($cancelllations[$_booking->BookingDetails->BookingId])){
                                            $total_hotel_cancell = 1;
                                        }
                                    }
                                }

                                foreach ($booking->tour_data as $i => $_booking){
                                    if(!empty($_booking->BookingId)){
                                        if (!empty($cancelllations[$_booking->BookingId])){
                                            $total_tour_cancell = 1;
                                        }
                                    }
                                }

                                foreach ($booking->transfer_data as $i => $_booking){
                                    if(!empty($_booking->BookingId)){
                                        if (!empty($cancelllations[$_booking->BookingId])){
                                            $total_transfer_cancell = 1;
                                        }
                                    }
                                }
                            @endphp

                            <tr>
                                <td>{{crc32($booking->id)}}</td>
                                <td>{{ $booking->created_at->format('jS M Y H:ia') }}</td>
                                <td>{{($booking->payment_type)}}</td>
                                <td>
                                    {{-- @if( $total_hotel_cancell == 0 && $total_tour_cancell==0)

                                    @else
                                        @if(empty($booking->is_paid))
                                            <a href="{{ route('updateourbooking.status', [$booking->id]) }}" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure to pay using wallet?')">Pay using Wallet</a>
                                            <a href="" class="btn btn-sm btn-warning"  onclick="return confirm('Are you sure to pay using online?')">Pay Online</a>
                                        @else
                                            <span>Completed</span>
                                        @endif
                                    @endif --}}
                                    {{-- @if (!empty($total_transfer_cancell) || !empty($total_tour_cancell) || !empty($total_hotel_cancell))
                                        <strong class="text-danger">Cancelled</strong> --}}
                                    @if($booking->is_paid)
                                        <span>Completed</span>
                                    @elseif (empty($booking->is_paid))
                                        <a href="{{ route('updateourbooking.status', [$booking->id]) }}" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure to pay using wallet?')">Pay using Wallet</a>
                                        <a href="" class="btn btn-sm btn-warning"  onclick="return confirm('Are you sure to pay using online?')">Pay Online</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mybookingbyid.show', [$booking->id, ]) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center">
                    <img src="{{ asset('assets/img/empty-list.png') }}" alt="" class="" />
                </div>
            @endif
        </div>

    </div>
</div>
@endsection