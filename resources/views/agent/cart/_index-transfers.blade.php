@if(!empty($transfer_cart))

    <div class="_cart__header">Transfer Item(s) </div>

    @php
        $total_transfer_rate = 0;
    @endphp

    @foreach ($transfer_cart as $key => $_item)
        @php
            $title = $_item['transfer']->title;
            $type = $_item['transfer']->type;
            $details = $_item['transfer']->details;
            $adult_price = $_item['transfer']->adult_price;
            $child_price = $_item['transfer']->child_price;
            $featured_image = $_item['transfer']->featured_image;
            $transfer_date = $_item['form']['form']['transfer_date'];

            // dd($child_price);

            $total_transfer_rate += (
                ($_item['form']['form']['adult'] * \App\Helpers\PriceHelper::getHikedHotelPrice($adult_price, Auth::user())) +
                ($_item['form']['form']['child'] * \App\Helpers\PriceHelper::getHikedHotelPrice($child_price, Auth::user()))
            );
        @endphp

        <div class="_cart__item">

            <div class="_checkout__set">
                <div class="_cart__item__title">
                    <strong>{{ $title }}</strong>
                    <a class="pull-right" href="{{ route('transfercart.remove', [$key]) }}">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        <span>Remove</span>
                    </a>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <span>Transfer date:</span>
                        <strong class="_cart__item__price">{{ $transfer_date}}</strong>
                    </div>
                    <div class="col-sm-4">
                        <span class="_cart__item__status">Available</span>

                    </div>
                    <div class="col-sm-4">
                        <strong>
                            {{ $allSymbol->sign }}
                            {{
                                // ($_item['form']['form']['adult'] * $adult_price) +  ($_item['form']['form']['child'] * $child_price)
                                ($_item['form']['form']['adult'] * \App\Helpers\PriceHelper::getHikedHotelPrice($adult_price, Auth::user()))
                                +
                                ($_item['form']['form']['child'] * \App\Helpers\PriceHelper::getHikedHotelPrice($child_price, Auth::user()))
                            }}
                        </strong>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <p>
                    <strong>Cancellation Policy</strong>
                </p>
                <p>
                    No charge if cancelled before
                    @php
                        $cancellation_last_day = strtotime('-7day', strtotime($transfer_date));
                    @endphp
                    <strong>{{ date('Y-m-d', $cancellation_last_day) }}</strong>
                    <br>
                    For Cancellation please email us <a href="mailto:online@canvasvacations.net">online@canvasvacations.net</a>
                    <br>
                    No cancellation will be accepted without email.
                </p>
            </div>

        </div>
    @endforeach

    <div class="_cart__footer">
        <div class="_cart__total">Grand Total :  {{ $allSymbol->sign }} {{ $total_transfer_rate}}</div>
    </div>

    {{-- <div class="_cart__footer">
        <div class="_cart__total">Grand Total :  INR {{ $total_transfer_rate}}</div>
        <hr />
        <div class="text-right">
            <a class="btn btn-primary" href="{{ route('home') }}">Add More Service</a>
            <a class="btn btn-primary" href="{{ route('cart.checkout') }}">Proceed Next</a>
        </div>
    </div> --}}

@endif