@extends('layouts.agent')

@section('content')

    @php
      //dd($booking_data);
        $tour_date =$booking_data->form->form->tour_date ;
        $tour_date_new = strtotime(date("d-M-Y", strtotime($tour_date)));
        $today = strtotime(date('d-M-Y'));
        $datediff = $tour_date_new - $today;
        $no_of_days = round($datediff / (60 * 60 * 24));
        $tour_date_new_1 = (date("d-m-Y", strtotime($tour_date)));
        $nationality = $booking_data->form->form->nationality;
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

            <h2>Cancellation Charges</h2>

            <table class="_checkout__table">
                <thead>
                    <tr>
                        <th> Day(s)</th>
                        <th>Refund Amount for Adult(s)</th>
                        <th>Refund Amount for Child(ren)</th>
                        <th>Refund Amount for Infant(s)</th>
                    </tr>
                </thead>
                <tbody>

                        <tr>
                            <td>

                                @php
                                    $cancellation_data = $booking_data->tour->cancellation_date;
                                    foreach ($cancellation_data->cancellation_date as $_index => $days) {
                                        if ($no_of_days >= $days) {
                                            $cancellation_policy_index = $_index;
                                        }else{
                                            $cancellation_policy_index ="";
                                        }
                                    }
                                   
                                @endphp

                                @if(!empty($booking_data->tour->cancellation_date))
                                    @foreach ($booking_data->tour->cancellation_date->cancellation_date as $_gdata)
                                    <p>{{ $_gdata }}</p>
                                    @endforeach
                                @endif

                            </td>
                           
                            <td>
                                @if(!empty($booking_data->tour->cancellation_date->adult_amount))
                                        @foreach ($booking_data->tour->cancellation_date->adult_amount as $_adult_amount)
                                        @php
                                            $refund_adult_amount = $total_adult_amount*$_adult_amount/100 ;
                                        @endphp
                                        <p> {{ $refund_adult_amount }} </p>
                                        @endforeach
                                @endif
                            </td>                           
                            <td>
                                @if(!empty($booking_data->tour->cancellation_date->child_amount))
                                        @foreach ($booking_data->tour->cancellation_date->child_amount as $_child_amount)
                                        @php
                                            $refund_child_amount = $total_child_amount*$_child_amount/100 ;
                                        @endphp
                                        <p> {{ $refund_child_amount }} </p>
                                        @endforeach
                                @endif
                            </td>
                            <td>
                                @if(!empty($booking_data->tour->cancellation_date->infant_amount))
                                            @foreach ($booking_data->tour->cancellation_date->infant_amount as $_infant_amount)
                                            @php
                                                $refund_infant_amount = $total_infant_amount*$_child_amount/100 ;
                                            @endphp
                                            <p> {{ $refund_infant_amount }} (%)</p>
                                            @endforeach
                                    @endif
                            </td>

                        </tr>

                </tbody>
            </table>
            <p>Tour Date : {{$tour_date_new_1}} Cancellation Date : {{date('d-m-Y')}} No of Days : {{$no_of_days}}</p>
            @php
                if (empty($cancellation_policy_index)) {
                    echo "No cancellation policy found";
                } else  {
                    $adult_policy_per =   $booking_data->tour->cancellation_date->adult_amount[$cancellation_policy_index];
                    $refund_policy_adult_amount = $total_adult_amount*$adult_policy_per/100 ;
                    $child_policy_per =   $booking_data->tour->cancellation_date->child_amount[$cancellation_policy_index];
                    $refund_child_amount = $total_child_amount*$child_policy_per/100 ;
                    $infant_policy_per =   $booking_data->tour->cancellation_date->infant_amount[$cancellation_policy_index];
                    $refund_infant_amount = $total_infant_amount*$infant_policy_per/100 ;
                    $total_policy_amount = $refund_policy_adult_amount+$refund_child_amount+$refund_infant_amount;
                    echo "<p>Total refund amount INR : ".$total_policy_amount."</p>";
                }
            @endphp
            
            <form action="{{ route('booking.cancel.update', [$booking->id, $booking_id]) }}" method="POST">
                <div class="text-right">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-lg btn-primary">Confirm Booking Cancellation</button>
                </div>
            </form>

            @foreach ($booking->tour_data as $booking_data)
                @if($booking_data->BookingId == $booking_id)
                        @include('agent.booking._mybooking-tour-data')
                @endif
            @endforeach

        </div>
    </div>

@endsection
