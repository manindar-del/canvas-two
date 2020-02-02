@if(!empty($transfer_cart))

    @php
        $y =0;
        $x = 0;
        $grand_total_transfer_rate = 0;
    @endphp

    @foreach ($transfer_cart as $key => $_item)
        @php
            $title = $_item['transfer']->title;
            $type = $_item['transfer']->type;
            $details = $_item['transfer']->details;
            $pick_up_time = $_item['transfer']->pick_up_time;
            $start_time = $_item['transfer']->start_time;
            $end_time = $_item['transfer']->end_time;
            $adult_price = $_item['transfer']->adult_price;
            $child_price = $_item['transfer']->child_price;
            $featured_image = $_item['transfer']->featured_image;
            $transfer_date = $_item['form']['form']['transfer_date'];
            //print_r($_item['form']['form']);
            $adult = $_item['form']['form']['adult'];
            $child = $_item['form']['form']['child'];
            $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
            $grand_total_transfer_rate += $total_transfer_rate;
            $total_member = $adult + $child;
            $items_transfer[] = [
                'date' => $transfer_date,
                'type' => $type,
                'members' => $total_member,
                'adult' => $adult,
                'adult_price' => $adult_price,
                'child' => $child,
                'child_price' => $child_price,
                'total_price' => $total_transfer_rate
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
                            <input type="text" class="form-control" name="transfer_cart[{{$key}}][{{$x}}][{{$i}}][salutation]" placeholder="Salutation" required />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="transfer_cart[{{$key}}][{{$x}}][{{$i}}][first_name]" placeholder="FirstName" required />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" name="transfer_cart[{{$key}}][{{$x}}][{{$i}}][last_name]" placeholder="LastName" required />
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" name="transfer_cart[{{$key}}][{{$x}}][{{$i}}][is_child]" />
                            <span>Is Child?</span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="transfer_cart[{{$key}}][{{$x}}][{{$i}}][age]" placeholder="Age" required />
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="_specialRequest">
            <div class="row">
                <div class="col-xs-12">
                    <label for="city" class="control-label">Special Request</label>
                    <textarea type="text" name="transfer_special_requests[{{$key}}]" class="form-control"
                    placeholder="Share Hotel Details and Flight Information Correctly for hassle Free Experience,Extra Charges Will Be Applicable For Hotel in Outskirts Zone,For SIC Zone Details please refer Description PDF &hellip;"></textarea>
                </div>
            </div>
        </div>

        {{-- <div class="_policy">
            <h2 class="_policy__title">Cancellation Policy *</h2>
            @php
            //dd($_item['transfer']->cancellation_date->cancellation_date)
            @endphp
            <div class="row">
                <div class="col-md-4">
                    @if(!empty($_item['transfer']->cancellation_date->cancellation_date))
                        @foreach ($_item['transfer']->cancellation_date->cancellation_date as $_gdata)
                            <p>Cancellation Charges Before {{ $_gdata }} Day(s)</p>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-2">
                    @if(!empty($_item['transfer']->cancellation_date->adult_amount))
                        @foreach ($_item['transfer']->cancellation_date->adult_amount as $_adult_amount)
                            <p> Adult: {{ $_adult_amount }}%</p>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-3">
                    @if(!empty($_item['transfer']->cancellation_date->child_amount))
                        @foreach ($_item['transfer']->cancellation_date->child_amount as $_child_amount)
                            <p>Children: {{ $_child_amount }}%</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div> --}}

    @endforeach

    <h2 class="_checkout__headline">Booking Amount Breakup</h2>

    <table class="_checkout__table">
        <thead>
            <tr>
                <th>Transfer Date	</th>
                <th>Type</th>
                <th>Price	</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items_transfer as $_item)
                <tr>
                    <td>{{ $_item['date']}}</td>
                    <td>{{ ucwords($_item['type']) }}</td>
                    <td>
                        <p>
                            @if(!empty($_item['adult_price']))
                                {{-- Adult :  --}}
                                {{$_item['adult_price']}} X {{$_item['adult']}} Adult(s) = {{ $_item['adult_price'] * $_item['adult'] }}
                            @endif
                            </p>
                        <p>
                            @if(!empty($_item['child_price']))
                                {{-- Child :  --}}
                                {{$_item['child_price']}} X {{$_item['child']}} Child(ren) = {{ $_item['child_price'] * $_item['child'] }}
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
                <td><input type="hidden" name="transfer_grand_total" value="{{ $grand_total_transfer_rate}}">{{ $allSymbol->sign }}{{ $grand_total_transfer_rate}}</td>
            </tr>
        </tfoot>
    </table>
@endif