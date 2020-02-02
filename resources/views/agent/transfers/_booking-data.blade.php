<div class="_status" >

    @php
        // dd($_booking)
        // dd($_booking->BookingDetails->BookingCurrency)
    @endphp

    <div class="_status__header">
        @if ('Confirmed' == $_booking->BookingDetails->BookingStatus && empty($cancelllations[$_booking->BookingDetails->BookingId]))
            <img src="{{ asset('images/confirmed.png') }}" alt="" />
        @else
            <img src="{{ asset('images/error.png') }}" alt="" />
        @endif
        @if (!empty($cancelllations[$_booking->BookingDetails->BookingId]))
            @php
                $_cancellation = $cancelllations[$_booking->BookingDetails->BookingId];
            @endphp
            <div class="_status__headline text-danger">Booking Cancelled</div>
            <div class="_status__title text-danger">
                Amount Charged:
                <strong>{{ $_cancellation->currency }} {{ ceil($_cancellation->charges) }}</strong>
            </div>
        @else
            <div class="_status__headline {{ 'Confirmed' != $_booking->BookingDetails->BookingStatus ? 'text-danger' : '' }}">{{ $_booking->BookingDetails->BookingStatus }}</div>
            <div class="_status__title {{ 'Confirmed' != $_booking->BookingDetails->BookingStatus ? 'text-danger' : '' }}">{{ $_booking->BookingRequest->Booking->Name }}</div>
        @endif
    </div>

    <div class="_status__info">
        <div class="row">
            @if (!empty($_booking->BookingDetails->BookingId))
                <div class="col-sm-4">
                    <div class="_status__info__title">Booking Id: {{ $_booking->BookingDetails->BookingId }}</div>
                </div>
            @endif
            @if (!empty($_booking->BookingDetails->BookingCode))
                <div class="col-sm-4">
                    <div class="_status__info__title">Booking Code: {{ $_booking->BookingDetails->BookingCode }}</div>
                </div>
            @endif
            @if (!empty($_booking->BookingDetails->BookingStatus))
                <div class="col-sm-4">
                    <div class="_status__info__title">Status: {{ $_booking->BookingDetails->BookingStatus }}</div>
                </div>
            @endif
            @if (!empty($_booking->BookingDetails->BookingCurrency) && !empty($_booking->BookingDetails->BookingPrice))
                <div class="col-sm-4">
                    <div class="_status__info__title">Price
                        <span class="_status__info__headline">{{ $_booking->BookingDetails->BookingCurrency }} {{ ceil($_booking->BookingDetails->BookingPrice) }}</span>
                    </div>
                </div>
            @endif
            @if (!empty($_booking->BookingRequest->Booking->ArrivalDate))
                <div class="col-sm-4">
                    <div class="_status__info__title">Check In: {{ $_booking->BookingRequest->Booking->ArrivalDate }}</div>
                </div>
            @endif
            @if (!empty($_booking->BookingRequest->Booking->DepartureDate))
                <div class="col-sm-4">
                    <div class="_status__info__title">Check Out: {{ $_booking->BookingRequest->Booking->DepartureDate }}</div>
                </div>
            @endif
        </div>
    </div>

    @foreach ($_booking->BookingRequest->Booking->RoomDetails as $_RoomDetail)

        @php
            $adults = explode('|', $_RoomDetail->Adults);
            $children = explode('|', $_RoomDetail->Children);
            $types = explode('|', $_RoomDetail->Type);
            $rates = explode('|', $_RoomDetail->TotalRate);
        @endphp

        <div class="_status__table ">
            <div class="_status__table__head primary">
                <div class="row">
                    <div class="col-xs-5">
                        <div class="_status__table__th">Room</div>
                    </div>
                    <div class="col-xs-2">
                        <div class="_status__table__th text-center">Price</div>
                    </div>
                    <div class="col-xs-2">
                        <div class="_status__table__th text-center">Adult</div>
                    </div>
                    <div class="col-xs-2">
                        <div class="_status__table__th text-center">Children</div>
                    </div>
                </div>
            </div>
            <div class="_status__table__body">
                @for ($i = 0; $i < $_RoomDetail->TotalRooms; $i++)
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="_status__table__td">
                                Room #{{ $i + 1 }}
                                <span class="_status__table__td__text">{{ $types[$i] }}</span>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="_status__table__td text-center">{{ ceil($rates[$i]) }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="_status__table__td text-center">{{ $adults[$i] }}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="_status__table__td text-center">{{ $children[$i] }}</div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        @foreach ($_RoomDetail->Guests as $_guest)

            @php
                if (!is_array($_guest)) {
                    $_guest = [$_guest];
                }
            @endphp

            <div class="_status__table">
                <div class="_status__table__head secondary">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="_status__table__th">Salutation</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__th  text-center">First Name</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__th text-center">Last Name</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__th text-center">Age</div>
                        </div>
                    </div>
                </div>
                <div class="_status__table__body">
                    @foreach ($_guest as $_guest_data)
                        @php
                            // dd($_guest_data);
                            if (!is_array($_guest_data)) {
                                $_guest_data = [$_guest_data];
                            }
                        @endphp
                        @foreach ($_guest_data as $_guest)
                            @php
                                if (isset($_guest->Guest)) {
                                    $_guest = $_guest->Guest;
                                }
                                if (!is_array($_guest)) {
                                    $_guest = [$_guest];
                                }
                            @endphp
                            @foreach ($_guest as $_guest_info)
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="_status__table__td">{{ $_guest_info->Salutation }}</div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="_status__table__td text-center">{{ $_guest_info->FirstName }}</div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="_status__table__td text-center">{{ $_guest_info->LastName }}</div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="_status__table__td text-center">{{ $_guest_info->Age }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach

                </div>
            </div>
        @endforeach

        <div class="row">
            <div class="clearfix">&nbsp;</div>
            <div class="col-xs-12 text-center">
                <a href="{{ route('booking.invoice', [$booking->id, $_booking->BookingDetails->BookingId]) }}" class="btn ticker-btn ">Invoice</a>&nbsp;&nbsp;
                @php
                    // dd($_booking)
                @endphp
                <a href="{{ route('booking.voucher', [$booking->id, $_booking->BookingDetails->BookingId]) }}" class="btn ticker-btn ">Voucher</a>
            </div>
        </div>

    @endforeach

</div>