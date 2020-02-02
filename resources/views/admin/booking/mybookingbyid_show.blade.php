@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        @if ($all_booking->count())

            <ul class="nav nav-tabs">
                {{-- <li class="active"><a data-toggle="tab" href="#home">Hotel</a></li> --}}
                <li class="active"><a data-toggle="tab" href="#tours">Tour</a></li>
                <li><a data-toggle="tab" href="#transfers">Transfer</a></li>
            </ul>

            <div class="tab-content">

                {{-- <div id="home" class="tab-pane">

                    <table id="myTable1" class="table table-striped table-bordered" cellspacing="0" width="100%">

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
                                                    <strong class="text-danger"> Booking Cancelled</strong>
                                                @else
                                                    <a
                                                        href="{{ route('bookingadmin.cancel.index', [$id, $_booking->BookingDetails->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif

                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('bookingadmin.show', [$id,]) }}#{{ $_booking->BookingDetails->BookingId }}" class="btn btn-sm btn-info"  target="_blank">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div> --}}

                <div id="tours" class="tab-pane fade in active">

                    <table id="myTable2" class="table table-striped table-bordered" cellspacing="0" width="100%">

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
                                    if(!empty($_booking->BookingId)) {
                                        $BookingId = $_booking->BookingId;
                                    } else {
                                        $BookingId ="";
                                    }
                                    $booking_complete = !empty($_booking->BookingId);
                                    if(!empty($_booking->BookingId)) {
                                        //echo $BookingId;
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
                                                    <span class="text-danger"> Booking Cancelled</span>
                                                @elseif (date('Y-m-d') <= $cancellation_last_day)
                                                    <a
                                                        href="{{ route('tourbookingadmin.cancel.index', [$id, $_booking->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('bookingadmin.show', [$id,$_booking->BookingId ]) }}" class="btn btn-sm btn-info" target="_blank">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div id="transfers" class="tab-pane fade">

                    <table id="myTable3" class="table table-striped table-bordered" cellspacing="0" width="100%">

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
                                    if(!empty($_booking->BookingId)) {
                                        $BookingId = $_booking->BookingId;
                                    } else {
                                        $BookingId ="";
                                    }
                                    $booking_complete = !empty($_booking->BookingId);
                                    if(!empty($_booking->BookingId)) {
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
                                                $booking_date =  date('Y-m-d', strtotime($transfer_date));
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
                                                    <strong class="text-danger"> Booking Cancelled</strong>
                                                @elseif (date('Y-m-d') <= $cancellation_last_day)
                                                    <a
                                                        href="{{ route('transferbookingadmin.cancel.index', [$id, $_booking->BookingId]) }}"
                                                        class="btn btn-primary btn-sm"
                                                    >Cancel Booking</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if ($booking_complete)
                                            <a href="{{ route('bookingadmin.show', [$id,$_booking->BookingId ]) }}" class="btn btn-sm btn-info" target="_blank">View Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <a class="btn btn-info" href="{{ route('admin.booking.index') }}">Back to All Booking</a>

            </div>

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

@push('footer-bottom')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/nouislider.min.js') }}"></script>
    <script>
        jQuery('#myTable1').dataTable({
            sort: [],
            "order": [[ 0, "desc" ]],
            responsive: true
        }).show();

        jQuery('#myTable2').dataTable({
            sort: [],
            "order": [[ 0, "desc" ]],
            responsive: true
        }).show();

        jQuery('#myTable3').dataTable({
            sort: [],
            order: [[ 0, "desc" ]],
            responsive: true
        }).show();
    </script>
@endpush
