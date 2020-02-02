@if(!empty($tour_cart))
    @php
        $y =0;
        $x = 0;
        $grand_total_tour_rate = 0;
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
            //print_r($_item['form']['form']);
            $adult = $_item['form']['form']['adult'];
            $child = $_item['form']['form']['child'];
            $infant = $_item['form']['form']['infant'];
            $total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
            $grand_total_tour_rate += $total_tour_rate;
            $total_member = $adult+$child+$infant;
            $items_tour[] = [
                'date' => $tour_date,
                'type' => $type,
                'members' => $total_member,
                'adult' => $adult,
                'adult_price' => $adult_price,
                'child' => $child,
                'child_price' => $child_price,
                'infant' => $infant,
                'infant_price' => $infant_price,

                'total_price' => $total_tour_rate
            ];
        @endphp

        <h2 class="_checkout__headline">
            Booking Details - {{ $title }}
        </h2>

        <div class="_checkout__group">
            @for ($i = 0; $i < $total_member; $i++)
                <div class="_checkout__info">
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][salutation]" placeholder="Salutation" required />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][first_name]" placeholder="FirstName" required />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][last_name]" placeholder="LastName" required />
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][is_child]" />
                            <span>Is Child?</span>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][is_infant]" />
                            <span>Is Infant?</span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="tour_cart[{{$key}}][{{$x}}][{{$i}}][age]" placeholder="Age" required />
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="_specialRequest">
            <div class="row">
                <div class="col-xs-12">
                    <label for="city" class="control-label">Special Request</label>
                    <textarea type="text" name="tour_special_requests[{{$key}}]" class="form-control"
                    placeholder="Share Hotel Details and Flight Information Correctly for hassle Free Experience,Extra Charges Will Be Applicable For Hotel in Outskirts Zone,For SIC Zone Details please refer Description PDF&hellip;"></textarea>
                </div>
            </div>
        </div>

        {{-- <div class="_policy">
            <h2 class="_policy__title">Cancellation Policy *</h2>
            @php
            //dd($_item['tour']->cancellation_date->cancellation_date)
            @endphp
            <div class="col-md-4">
                @if(!empty($_item['tour']->cancellation_date->cancellation_date))
                    @foreach ($_item['tour']->cancellation_date->cancellation_date as $_gdata)
                        <p>Cancellation Charges Before   {{ $_gdata }} Day(s)</p>
                    @endforeach
                @endif
            </div>
            <div class="col-md-2">
                @if(!empty($_item['tour']->cancellation_date->adult_amount))
                    @foreach ($_item['tour']->cancellation_date->adult_amount as $_adult_amount)
                        <p> Adult :   {{ $_adult_amount }}(%)</p>
                    @endforeach
                @endif
            </div>
            <div class="col-md-3">
                @if(!empty($_item['tour']->cancellation_date->child_amount))
                    @foreach ($_item['tour']->cancellation_date->child_amount as $_child_amount)
                        <p> Children :   {{ $_child_amount }}(%)</p>
                    @endforeach
                @endif
            </div>
            <div class="col-md-3">
                @if(!empty($_item['tour']->cancellation_date->infant_amount))
                    @foreach ($_item['tour']->cancellation_date->infant_amount as $_infant_amount)
                        <p>Infant :    {{ $_infant_amount }} (%)</p>
                    @endforeach
                @endif
            </div>
            <div class="clearfix"></div>
        </div> --}}

    @endforeach

    <h2 class="_checkout__headline">Booking Amount Breakup</h2>

    <table class="_checkout__table">
        <thead>
            <tr>
                <th>Tour Date	</th>
                <th>Type</th>
                <th>Price</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items_tour as $_item)
                <tr>
                    <td>{{ $_item['date']}}</td>
                    <td>{{ $_item['type']}}</td>
                    <td>
                        <p>
                            @if(!empty($_item['adult_price']))
                            Adult : {{$_item['adult_price']}} X {{$_item['adult']}} Adult(s) = {{ $_item['adult_price'] * $_item['adult'] }}
                            @endif
                        </p>
                        <p>
                            @if(!empty($_item['child_price']))
                            Adult : {{$_item['child_price']}} X {{$_item['child']}} Child(ren) = {{ $_item['child_price'] * $_item['child'] }}
                            @endif
                        </p>
                        <p>
                            @if(!empty($_item['infant_price']))
                            Adult : {{$_item['infant_price']}} X {{$_item['infant']}} Infant(s) = {{ $_item['infant_price'] * $_item['infant'] }}
                            @endif
                        </p>
                    </td>
                    <td>{{ $allSymbol->sign }}{{ $_item['total_price']}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>Total Rate</td>
                <td><input type="hidden" name="tour_grand_total" value="{{ $grand_total_tour_rate}}">{{ $allSymbol->sign }}{{ $grand_total_tour_rate}}</td>
            </tr>
        </tfoot>
    </table>
@endif