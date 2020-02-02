@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @if ($all_booking->count())
        <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Hotel</a></li>
    <li><a data-toggle="tab" href="#menu1">Tour</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
    <table class="_checkout__table">
                <thead>
                    <th>Hotel</th>
                    <th>Booking ID</th>
                    <th>Booking Code</th>
                    <th>Booking Status</th>
                    <th>Payment Status</th>
                    <th></th>
                </thead>
                <tbody>

                    @foreach ($all_booking as $booking)

                        @php
                          //dd($booking);
                        @endphp

                        @foreach ($booking->data as $i => $_booking)
                            @php
                                 //dd($_booking);
                                $booking_complete = !empty($_booking->BookingDetails->BookingId);
                            @endphp
                            <tr>
                                <td>{{ $_booking->BookingRequest->Booking->Name }}</td>
                                <td>{{ $_booking->BookingDetails->BookingId or '-' }}</td>
                                <td>{{ $_booking->BookingDetails->BookingCode or '-' }}</td>
                                <td>
                                    @if (!empty($_booking->BookingDetails->BookingId))
                                        @if (empty($cancelllations[$_booking->BookingDetails->BookingId]))
                                            <span>{{ $_booking->BookingDetails->BookingStatus }}</span>
                                        @else
                                            <span class="text-danger">Cancelled</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($booking->payment_type ))
                                        @if ($booking->payment_type == "Later")
                                        <a href="" class="btn btn-sm btn-warning">Pay using Wallet</a>
                                         <a href="" class="btn btn-sm btn-warning">Pay Online</a>
                                        @else
                                            <span>Completed</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($booking_complete)
                                        @php
                                            $booking_date_parts = explode('/', $_booking->BookingRequest->Booking->DepartureDate);
                                            $booking_date = $booking_date_parts[2] . '-' . $booking_date_parts[1] . '-' . $booking_date_parts[0];
                                        @endphp
                                        @if (empty($cancelllations[$_booking->BookingDetails->BookingId]))
                                            @if ($booking_date > date('Y-m-d'))
                                                <a
                                                    href="{{ route('booking.cancel.index', [$booking->id, $_booking->BookingDetails->BookingId]) }}"
                                                    class="btn btn-primary btn-sm"
                                                >Cancel Booking</a>
                                            @endif
                                        @endif
                                    @endif
                                    @if ($booking_complete)
                                        <a href="{{ route('booking.show', [$booking->id,]) }}#{{ $_booking->BookingDetails->BookingId }}" class="btn btn-sm btn-info">View Details</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @endforeach
                </tbody>
            </table>
    </div>
    <div id="menu1" class="tab-pane fade">
    <table class="_checkout__table">
                <thead>
                    <th>Tour</th>

                    <th>Booking ID</th>
                    <th>Booking Status</th>
                     <th>Payment Status</th>
                    <th></th>
                </thead>
                <tbody>

                    @foreach ($all_booking as $booking)

                        @php
                          //dd($booking);
                        @endphp

                        @foreach ($booking->tour_data as $i => $_booking)
                            @php
                                 //dd($_booking->BookingId);
                                if(!empty($_booking->BookingId)) $BookingId = $_booking->BookingId;
                                else{$BookingId ="";}
                                $booking_complete = !empty($_booking->BookingId);
                            @endphp
                            <tr>
                                <td>{{ $_booking->tour->title }}</td>

                                <td>{{ $BookingId }}</td>
                                 <td>{{ $_booking->BookingStatus }}</td>
                                 <td>
                                    @if (!empty($booking->payment_type ))
                                        @if ($booking->payment_type == "Later")
                                        <a href="" class="btn btn-sm btn-warning">Pay using Wallet</a>&nbsp;
                                         <a href="" class="btn btn-sm btn-warning">Pay Online</a>
                                        @else
                                            <span>Completed</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                @if ($booking_complete)
                                        @php
                                            $tour_date = $_booking->form->form->tour_date;
                                            $booking_date =  date('Y-m-d',strtotime($tour_date));
                                        @endphp

                                            @if ($booking_date > date('Y-m-d'))
                                                <a
                                                    href="{{ route('tourbooking.cancel.index', [$booking->id, $_booking->BookingId]) }}"
                                                    class="btn btn-primary btn-sm"
                                                >Cancel Booking</a>

                                        @endif
                                    @endif
                                    @if ($booking_complete)
                                        <a href="{{ route('mybooking.show', [$booking->id,$_booking->BookingId ]) }}" class="btn btn-sm btn-info">View Details</a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                    @endforeach
                </tbody>
            </table>
    </div>

  </div>


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
