{{-- Tour Cart Start --}}
@if(!empty($tour_cart))

    <div class="_cart__header">Tour Item(S)</div>

    @php
        $total_tour_rate = 0;
    @endphp

    @foreach ($tour_cart as $key => $_item)

        @php
            $title = $_item['tour']->title;
            $type = $_item['tour']->type;
            $details = $_item['tour']->details;
            $pick_up_time = $_item['tour']->pick_up_time;
            $start_time = $_item['tour']->start_time;
            $end_time = $_item['tour']->end_time;
            $adult_price = $_item['tour']->adult_price;
            $child_price = $_item['tour']->child_price;
            $infant_price = $_item['tour']->infant_price;
            $featured_image = $_item['tour']->featured_image;
            $tour_date = $_item['form']['form']['tour_date'];
                // print_r($_item['form']['form']['tour_date']);
            $total_tour_rate += ($_item['form']['form']['adult'] * $adult_price) +  ($_item['form']['form']['child'] * $child_price) + ($_item['form']['form']['infant'] * $infant_price);
        @endphp

        <div class="_cart__item">

            <div class="_cart__item__title">
                <strong>{{ $title }}</strong>
                <a class="pull-right" href="{{ route('tourcart.remove', [$key]) }}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    <span>Remove</span>
                </a>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <span>Tour date:</span>
                    <strong class="_cart__item__price">{{$tour_date}}</strong>
                </div>
                <div class="col-sm-4">
                    <span class="_cart__item__status">Available</span>
                </div>
                <div class="col-sm-4">
                    <span>
                        {{ $allSymbol->sign }}
                        {{ ($_item['form']['form']['adult'] * $adult_price) +  ($_item['form']['form']['child'] * $child_price) + ($_item['form']['form']['infant'] * $infant_price)}}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <strong>Pick Up Time:</strong>
                    <span>{{ $pick_up_time }}</span>
                </div>
                <div class="col-sm-3">
                    <strong>Start Time:</strong>
                    <span>{{ $start_time }}</span>
                </div>
                <div class="col-sm-3">
                    {{-- <strong>Meal:</strong>
                    <span></span> --}}
                </div>
            </div>

            <div class="alert alert-info">
                <p>
                    <strong>Cancellation Policy</strong>
                </p>
                <p>
                    No charge if cancelled before
                    @php
                        $cancellation_last_day = strtotime('-7day', strtotime($tour_date));
                    @endphp
                    <strong>{{ date('Y-m-d', $cancellation_last_day) }}</strong>
                    <br>
                    For Cancellation please email us <a href="online@canvasvacations.net">online@canvasvacations.net</a>
                    <br>
                    No cancellation will be accepted without email.
                    </strong>
                </p>
            </div>

        </div>
    @endforeach

    <div class="_cart__footer">
        <div class="_cart__total">Grand Total  :  {{ $allSymbol->sign }} {{ $total_tour_rate}}</div>
    </div>

@endif
{{-- Tour Cart End --}}