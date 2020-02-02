<div class="_status" >

    @php
    //dd($booking_data);
         $adult = $booking_data->form->form->adult;
         $child = $booking_data->form->form->child;
         $infant = $booking_data->form->form->infant;
         $pick_up_time = $booking_data->tour->pick_up_time;
         $start_time = $booking_data->tour->start_time;
         $adult_price = $booking_data->tour->adult_price;
         $child_price = $booking_data->tour->child_price;
         $infant_price = $booking_data->tour->infant_price;
         $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
         //$grand_total_tour_rate += $total_tour_rate;
           $BookingId = $booking_data->BookingId;
          //echo $segment = Request::segment(3);

    @endphp

    <div class="_status__header">
        @if (!empty($booking_data->BookingId))
            <img src="{{ asset('images/confirmed.png') }}" alt="" />
        @else
            <img src="{{ asset('images/error.png') }}" alt="" />
        @endif
        @if (empty($booking_data->BookingId))

            <div class="_status__headline text-danger">Booking Cancelled</div>

        @else
            <div class="_status__title">{{ $booking_data->tour->title }}</div>
        @endif
        @if( $booking->payment_type=="Later")
        <div class="_status__title text-danger">Payment Status : Due</div>
        @endif
    </div>

    <div class="_status__info">
        <div class="row">
            @if (!empty($booking_data->BookingId))
                <div class="col-sm-4">
                    <div class="_status__info__title">Booking Id: {{ $booking_data->BookingId }}</div>
                </div>
            @endif

            @if (!empty($booking_data->BookingId))
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
            @if (!empty($booking_data->form->form->tour_date))
                <div class="col-sm-4">
                    <div class="_status__info__title">Tour Date: {{ $booking_data->form->form->tour_date }}</div>
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
                        <div class="_status__table__th text-center">Children</div>
                    </div>
                </div>
            </div>
            <div class="_status__table__body">

                    <div class="row">
                        <div class="col-xs-5">
                            <div class="_status__table__td">

                                <span class="_status__table__td__text">{{  $booking_data->tour->type }}</span>
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
                 @foreach ($booking_data->members as $_guest_info)
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

        <div class="row">
        <div class="clearfix">&nbsp;</div>
            <div class="col-xs-12 text-center">
                <a target="_blank" href="{{ route('booking.tourinvoice', [$booking->id, $booking_data->BookingId]) }}" class="btn ticker-btn ">Invoice</a>&nbsp;&nbsp;
                @php
                    // dd($booking_data)
                @endphp
                <a target="_blank" href="{{ route('booking.tourvoucher', [$booking->id, $booking_data->BookingId]) }}" class="btn ticker-btn ">Voucher</a>
            </div>
        </div>

    {{-- @endif --}}

</div>

