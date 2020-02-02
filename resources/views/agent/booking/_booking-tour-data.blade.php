<div class="_status" >

    @php
        $adult = $_booking->form->form->adult;
        $child = $_booking->form->form->child;
        $infant = $_booking->form->form->infant;
        $pick_up_time = $_booking->tour->pick_up_time;
        $start_time = $_booking->tour->start_time;
        $adult_price = $_booking->tour->adult_price;
        $child_price = $_booking->tour->child_price;
        $infant_price = $_booking->tour->infant_price;
        $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
         //$grand_total_tour_rate += $total_tour_rate;

        $cancelled = false;
        foreach ($cancelllations as $_cancellation) {
            if ($_booking->BookingId == $_cancellation->booking_id) {
                $cancelled = true;
            }
        }

    @endphp

    <div class="_status__header">

        {{-- @if (!empty($_booking->BookingId) &&  !$cancelled)
            <img src="{{ asset('images/confirmed.png') }}" alt="" />
        @else
            <img src="{{ asset('images/error.png') }}" alt="" />
        @endif

        @if (empty($_booking->BookingId) || $cancelled)
            <div class="_status__headline text-danger">Booking Cancelled</div>
        @else
            <div class="_status__title">{{ $_booking->tour->title }}</div>
        @endif

        @if( $booking->payment_type=="Later" && ! $booking->is_paid)
            <div class="_status__title text-danger">Payment Status : Due</div>
        @endif --}}

        @if ('Confirmed' == $_booking->BookingStatus && empty($cancelllations[$_booking->BookingId]))
            <img src="{{ asset('images/confirmed.png') }}" alt="" />
        @else
            <img src="{{ asset('images/error.png') }}" alt="" />
            <h3 class="_status__headline _status__title text-danger"><strong>Cancelled</strong></h3>
        @endif

    </div>

    <div class="_status__info">
        <div class="row">
            @if (!empty($_booking->BookingId))
                <div class="col-sm-4">
                    <div class="_status__info__title">Booking Id: {{ $_booking->BookingId }}</div>
                </div>
            @endif

            @if (!empty($_booking->BookingId))
                <div class="col-sm-4">
                    <div class="_status__info__title">Status: {{$_booking->BookingStatus}}</div>
                </div>
            @endif
            @if (!empty($total_tour_rate))
                <div class="col-sm-4">
                    <div class="_status__info__title">Price
                        <span class="_status__info__headline">INR {{ ceil($total_tour_rate) }}</span>
                    </div>
                </div>
            @endif
            @if (!empty($_booking->form->form->tour_date))
                <div class="col-sm-4">
                    <div class="_status__info__title">Tour Date: {{ $_booking->form->form->tour_date }}</div>
                </div>
            @endif
            @if (!empty($pick_up_time))
                <div class="col-sm-4">
                    <div class="_status__info__title">Pick Up Time: {{ $pick_up_time }}</div>
                </div>
            @endif
            @if (!empty($start_time))
                <div class="col-sm-4">
                    <div class="_status__info__title">Start Time: {{ $start_time }}</div>
                </div>
            @endif

        </div>
    </div>

    <div class="_status__table ">
        <div class="_status__table__head primary">
            <div class="row">
                <div class="col-xs-5">
                    <div class="_status__table__th">Type</div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__th text-center">Adult</div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__th text-center">Children</div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__th text-center">Infant</div>
                </div>
            </div>
        </div>

        <div class="_status__table__body">
            <div class="row">
                <div class="col-xs-5">
                    <div class="_status__table__td">
                        <span class="_status__table__td">{{ ucwords($_booking->tour->type) }}</span>
                    </div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__td text-center">{{$adult}}</div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__td text-center">{{$child}}</div>
                </div>
                <div class="col-xs-2">
                    <div class="_status__table__td text-center">{{$infant}}</div>
                </div>
            </div>
        </div>
    </div>

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
            @foreach ($_booking->members as $_guest_info)
                @foreach ($_guest_info as $_members)
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="_status__table__td">{{ $_members->salutation }}</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__td text-center">{{ $_members->first_name }}</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__td text-center">{{ $_members->last_name }}</div>
                        </div>
                        <div class="col-xs-3">
                            <div class="_status__table__td text-center">{{ $_members->age }}</div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    @if ('Confirmed' == $_booking->BookingStatus && empty($cancelllations[$_booking->BookingId]))
        <div class="text-center">
            <a target="_blank" href="{{ route('booking.tourinvoice', [$booking->id, $_booking->BookingId]) }}" class="btn ticker-btn ">Invoice</a>&nbsp;&nbsp;
            @php
                // dd($_booking)
            @endphp
            <a target="_blank" href="{{ route('booking.tourvoucher', [$booking->id, $_booking->BookingId]) }}" class="btn ticker-btn ">Voucher</a>
        </div>
    @endif

</div>
