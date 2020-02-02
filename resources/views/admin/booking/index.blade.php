@extends('layouts.admin')

@section('content')
<div class="_page">

    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        @php
            //dd($cancelllations);
        @endphp

        @if ($all_booking->count())
            <table class="table table-stripped" id="booking" style="display: none;">

                <thead>
                    <th>ID</th>
                    <th>Booking ID</th>
                    <th>Agent</th>
                    <th>Payment Type</th>
                    <th>Payment Status</th>
                    <th>Details</th>
                </thead>

                <tbody>

                    @foreach ($all_booking as $booking)

                        @php
                            //dd($booking->data->BookingDetails);
                            $total_hotel_cancell = 0;
                            $total_tour_cancell = 0;
                            $total_transfer_cancell = 0;

                            foreach ($booking->data as $i => $_booking){
                                if(!empty($_booking->BookingDetails->BookingId)){
                                    if (empty($cancelllations[$_booking->BookingDetails->BookingId])){
                                        $total_hotel_cancell = 1;
                                    }
                                }
                            }


                                 //foreach ($booking->tour_data as $i => $_booking){
                                //if(!empty($_booking->BookingId)){
                                    //if (empty($cancelllations[$_booking->BookingId])){
                                        //$total_tour_cancell = 1;
                                    //}
                               // }
                           // }

                            //foreach ($booking->transfer_data as $i => $_booking){
                                //if(!empty($_booking->BookingId)){
                                    //if (empty($cancelllations[$_booking->BookingId])){
                                       // $total_transfer_cancell = 1;
                                   // }
                                //}
                            //}
                            $user = App\User::findOrFail($booking->user_id);
                            //dd($user->user_name);
                        @endphp

                        <tr>
                            <td>{{($booking->id)}}</td>
                            <td>{{crc32($booking->id)}}</td>
                            <td>{{($user->user_name)}}</td>
                                <td>{{($booking->payment_type)}}</td>
                            <td>
                                @if($total_hotel_cancell == 0 && $total_tour_cancell==0 && $total_transfer_cancell == 0)
                                    {{--  --}}
                                @else
                                    @if(empty($booking->is_paid))
                                        <!--<a href="{{ route('updateourbookingbyadmin.status', [$booking->id]) }}" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure to pay using wallet?')">Pay using Wallet</a>-->
                                        @else
                                        <span>Completed</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('mybookingbyidbyadmin.show', [$booking->id, ]) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>

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
        jQuery('#booking').dataTable({
            sort: [],
            "order": [[ 0, "desc" ]],
            responsive: true
        }).show();
    </script>
@endpush