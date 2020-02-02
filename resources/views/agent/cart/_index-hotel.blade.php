@if ( is_array($cart) && count($cart))

    <div class="_cart__header">Hotel Item(S)</div>

    @php
        $total = 0;
    @endphp

    @foreach ($cart as $key => $_item)
        @php
            $PreBookingDetails = $_item['prebook']->PreBookingDetails;
            $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
            $RoomDetail = $PreBooking->RoomDetails->RoomDetail;
            $CancellationInformation = $PreBooking->CancellationInformations->CancellationInformation;
            $currency = $PreBooking->Currency;
            $room_rate = explode('|', $RoomDetail->TotalRate);
            $room_rate = array_sum($room_rate);
            // var_dump($room_rate);
            // var_dump($RoomDetail->TotalRate);
            $total += ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($room_rate, Auth::user()));
            $room_types = explode('|', $RoomDetail->Type);
            if (!is_array($CancellationInformation)) {
                $CancellationInformation = [$CancellationInformation];
            }
            // dd($room_types);
        @endphp

        <div class="_cart__item">
            <div class="_cart__item__title">
                <strong>{{ $_item['hotel']->Name }}</strong>
                <a class="pull-right" href="{{ route('cart.remove', [$key]) }}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    <span>Remove</span>
                </a>
            </div>

            <div class="row">
                {{-- <div class="col-sm-8">{{ $RoomDetail->Type }}</div> --}}
                <div class="col-sm-4">
                    <span class="_cart__item__status">Available</span>
                    <strong class="_cart__item__price">{{ $PreBooking->Currency }} {{ ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($PreBookingDetails->BookingAfterPrice, Auth::user())) }}</strong>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <strong>Check In:</strong>
                    <span>{{ $PreBooking->ArrivalDate }}</span>
                </div>
                <div class="col-sm-3">
                    <strong>Check Out:</strong>
                    <span>{{ $PreBooking->DepartureDate }}</span>
                </div>
                <div class="col-sm-3">
                    {{-- <strong>Meal:</strong>
                    <span></span> --}}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h5>Rooming details</h5>
                    @foreach ($room_types as  $key => $value)
                        <div>
                            <strong>Room #{{ $key + 1 }}:</strong>
                            <span>{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h4>Cancellation Policy</h4>
                    @foreach ($CancellationInformation as $_cancellation_policy)
                        @if (!empty($_cancellation_policy->ChargeAmount))
                            <div>A cancellation penalty of
                                <strong class="text-danger">
                                    {{ $_cancellation_policy->Currency }}
                                    {{ ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_cancellation_policy->ChargeAmount, Auth::user())) }}
                                </strong>
                                will be applied if cancel from
                                <strong>{{ date('d-M-Y', strtotime($_cancellation_policy->StartDate)) }}</strong>
                                onward
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">

                </div>
            </div>
        </div>
    @endforeach


    <div class="_cart__footer">
        <div class="_cart__total">Grand Total : {{ $currency }} {{ $total }}</div>
        <hr />
        <!-- <div class="text-right">
            <a class="btn btn-primary" href="{{ route('home') }}">Add More Service</a>
            <a class="btn btn-primary" href="{{ route('cart.checkout') }}">Proceed Next</a>
        </div>
        -->
    </div>

    <!-- <div class="_remarks">
        <h4 class="_remarks__title">Important Remarks & Policies</h4>
        <p>
            <strong>Nationality & Domicile</strong>
        </p>
        <p>
            Passenger travelling to destination where guest is holding a local residency; Booking should be searched with Country of Residence as Nationality in order to avail the valid rates. (i.e. Indian National holding UAE Residence Permit should select Emirati as nationality for search). In case of wrong residency or nationality selected by user at the time of booking; the supplement charges may be applicable and need to be paid directly to the hotel by guest on check in/check out.
        </p>
        <p>Additional supplement charges may be charged by the Hotel (which the Guest have to pay directly at the hotel) If the lead guest’s Nationality is different than the Nationality of the other accompanied guests. For more details you can reach out to our operation Team for clarification.</p>
        <p>
            <strong>Group Reservation</strong>
        </p>
        <p>
            Our System is a FIT (Free Individual Traveler) Reservation system which allows maximum of 8 Rooms or 48 Passengers to be booked at one time. However any activity which has more than 3 rooms or 9 Passengers booked for the "same date of travel & same hotel of stay" combination is handled as per "group terms & conditions". In such cases we have rights to cancel any confirmed or vouchered reservation(s) without any prior intimation. You are requested to contact our nearest support office in case of group or reservation with more than 3 room requirement.
        </p>
    </div>
    -->

@endif