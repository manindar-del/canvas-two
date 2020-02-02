<div class="_status" >

    @php
        $adult = $_booking->form->form->adult;
        $child = $_booking->form->form->child;
        $adult_price = $_booking->transfer->adult_price;
        $child_price = $_booking->transfer->child_price;
        $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
        //$grand_total_transfer_rate += $total_transfer_rate;
    @endphp

    <div class="_status__header">
        @if (!empty($_booking->BookingId))
            <img src="{{ asset('images/confirmed.png') }}" alt="" />
        @else
            <img src="{{ asset('images/error.png') }}" alt="" />
        @endif
        @if (empty($_booking->BookingId))

            <div class="_status__headline text-danger">Booking Cancelled</div>

        @else
            <div class="_status__title">{{ $_booking->transfer->title }}</div>
        @endif
        @if( $booking->payment_type=="Later" && ! $booking->is_paid)
            <div class="_status__title">
                <span class="text-danger">Payment Status : Due</span>
            </div>
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
            @if (!empty($total_transfer_rate))
                <div class="col-sm-4">
                    <div class="_status__info__title">Price
                        <span class="_status__info__headline">INR {{ ceil($total_transfer_rate) }}</span>
                    </div>
                </div>
            @endif
            @if (!empty($_booking->form->form->transfer_date))
                <div class="col-sm-4">
                    <div class="_status__info__title">transfer Date: {{ $_booking->form->form->transfer_date }}</div>
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
                                <span class="_status__table__td">{{ ucwords($_booking->transfer->type) }}</span>
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <div class="_status__table__td text-center">{{$adult}}</div>
                        </div>
                        <div class="col-xs-2">
                            <div class="_status__table__td text-center">{{$child}}</div>
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

        <div class="row">
        <div class="clearfix">&nbsp;</div>
            @if( $booking->is_paid)
                <div class="col-xs-12 text-center">
                    <a href="{{ route('bookingadmin.transferinvoice', [$booking->id, $_booking->BookingId]) }}" class="btn btn-danger" target="_blank">Invoice</a>&nbsp;&nbsp;
                    @php
                        // dd($_booking)
                    @endphp
                    <a href="{{ route('bookingadmin.transfervoucher', [$booking->id, $_booking->BookingId]) }}" class="btn btn-danger" target="_blank">Voucher</a>
                </div>
            @endif
        </div>

    {{-- @endif --}}

</div>

