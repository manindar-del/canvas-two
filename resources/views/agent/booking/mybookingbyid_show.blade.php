@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        @if ($all_booking->count())

            <ul class="nav nav-tabs">
                {{-- <li class="active"><a data-toggle="tab" href="#hotel">Hotel</a></li> --}}
                <li class="active"><a data-toggle="tab" href="#tour">Tour</a></li>
                <li><a data-toggle="tab" href="#transfer">Transfer</a></li>
            </ul>

            <div class="tab-content">

                {{-- <div id="hotel" class="tab-pane fade in active">
                    <table class="_checkout__table">
                        <thead>
                            <th>Hotel</th>
                            <th>Booking ID</th>
                            <th>Booking Code</th>
                            <th>Booking Status</th>
                            <th>Booking Action</th>
                            <th></th>
                        </thead>

                        <tbody>

                            @foreach ($all_booking->data as $i => $_booking)

                                @php
                                    // dd($cancelllations);
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
                                        @if ($booking_complete)
                                            @php
                                                $booking_date_parts = explode('/', $_booking->BookingRequest->Booking->DepartureDate);
                                                $booking_date = $booking_date_parts[2] . '-' . $booking_date_parts[1] . '-' . $booking_date_parts[0];
                                            @endphp

                                            @if ($booking_date > date('Y-m-d'))

                                                @php
                                                    $cancelled = false;
                                                @endphp

                                                @foreach ($cancellation as $_cancellation)
                                                    @if ($_booking->BookingDetails->BookingId == $_cancellation['booking_id'])
                                                        @php
                                                            $cancelled = true;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @if ($cancelled)
                                                    <strong class="text-danger">Booking Cancelled</strong>
                                                @else
                                                    <a
                                                        href="{{ route('booking.cancel.index', [$id, $_booking->BookingDetails->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('booking.show', [$id,]) }}#{{ $_booking->BookingDetails->BookingId }}" class="btn btn-sm btn-info">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}

                <div id="tour" class="tab-pane fade in active">
                    <table class="_checkout__table">

                        <thead>
                            <th>Tour</th>
                            <th>Booking ID</th>
                            <th>Booking Status</th>
                            <th>Booking Action</th>
                            <th></th>
                        </thead>

                        <tbody>
                            @foreach ($all_booking->tour_data as $i => $_booking)
                                @php
                                    //dd($cancellation);
                                    if (!empty($_booking->BookingId)) {
                                        $BookingId = $_booking->BookingId;
                                    } else {
                                        $BookingId ="";
                                    }
                                    $booking_complete = !empty($_booking->BookingId);
                                    if (!empty($_booking->BookingId)) {
                                        $cancelllations_tour = DB::table('cancellations')->where('user_id', '$id')->get();
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $_booking->tour->title }}</td>
                                    <td>{{ $BookingId }}</td>

                                    <td>
                                        @if (!empty($BookingId))
                                            @if (empty($cancelllations[$BookingId]))
                                                <span>{{ $_booking->BookingStatus }}</span>
                                            @else
                                                <span class="text-danger">Cancelled</span>
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            @php
                                                $cancelled = false;
                                                $tour_date = $_booking->form->form->tour_date;
                                                $booking_date =  date('Y-m-d',strtotime($tour_date));
                                                $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($tour_date)));
                                            @endphp

                                            @if ($booking_date > date('Y-m-d'))
                                                @foreach ($cancellation as $_cancellation)
                                                    @if ($_booking->BookingId == $_cancellation['booking_id'])
                                                        @php
                                                            $cancelled = true;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @if ($cancelled)
                                                    <strong class="text-danger">Booking Cancelled</strong>
                                                @elseif (date('Y-m-d') <= $cancellation_last_day)
                                                    <a
                                                        href="{{ route('tourbooking.cancel.index', [$id, $_booking->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('mybooking.show', [$id,$_booking->BookingId ]) }}" class="btn btn-sm btn-info">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>{{-- tour --}}

                <div id="transfer" class="tab-pane fade">
                    <table class="_checkout__table">

                        <thead>
                            <th>Transfer</th>
                            <th>Booking ID</th>
                            <th>Booking Status</th>
                            <th>Booking Action</th>
                            <th></th>
                        </thead>

                        <tbody>
                            @foreach ($all_booking->transfer_data as $i => $_booking)
                                @php
                                    //dd($cancellation);
                                    if(!empty($_booking->BookingId)) $BookingId = $_booking->BookingId;
                                    else{$BookingId ="";}
                                    $booking_complete = !empty($_booking->BookingId);
                                    if(!empty($_booking->BookingId)){
                                        //echo $BookingId;
                                        $cancelllations_tour = DB::table('cancellations')->where('user_id', '$id')->get();
                                    }

                                @endphp
                                <tr>
                                    <td>{{ $_booking->transfer->title }}</td>
                                    <td>{{ $BookingId }}</td>
                                    <td>
                                        @if (!empty($BookingId))
                                            @if (empty($cancelllations[$BookingId]))
                                                <span>{{ $_booking->BookingStatus }}</span>
                                            @else
                                                <span class="text-danger">Cancelled</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking_complete)
                                            @php
                                                $cancelled = false;
                                                $transfer_date = $_booking->form->form->transfer_date;
                                                $booking_date =  date('Y-m-d',strtotime($transfer_date));
                                                $cancellation_last_day = date('Y-m-d', strtotime('-7day', strtotime($transfer_date)));
                                            @endphp

                                            @if ($booking_date > date('Y-m-d'))
                                                @foreach ($cancellation as $_cancellation)
                                                    @if ($_booking->BookingId == $_cancellation['booking_id'])
                                                        @php
                                                            $cancelled = true;
                                                        @endphp
                                                    @endif
                                                @endforeach

                                                @if ($cancelled)
                                                    <strong class="text-danger">Booking Cancelled</strong>
                                                @elseif (date('Y-m-d') <= $cancellation_last_day)
                                                    <a
                                                        href="{{ route('transferbooking.cancel.index', [$id, $_booking->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('mybooking.show', [$id, $_booking->BookingId]) }}" class="btn btn-sm btn-info">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>{{-- tour --}}

            </div>{{-- tab-content --}}

        @else

            {{-- <h2 class="text-center">
                You have not made any bookings yet.
                <br>
                <a href="{{ route('home') }}">
                    <small>Click here to make your first booking</small>
                </a>
            </h2> --}}

            <div class="text-center">
                <img src="{{ asset('assets/img/empty-list.png') }}" alt="" />
            </div>

        @endif

    </div>
</div>
@endsection
