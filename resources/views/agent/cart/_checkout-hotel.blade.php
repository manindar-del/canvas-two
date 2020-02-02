@if (!empty($cart) && count($cart))

    @php
        $total = 0;
        $items = [];
    @endphp

    @foreach ($cart as $key => $_item)

        @php
            // dd($_item);
            $hotel = $_item['hotel'];
            $PreBooking = $_item['prebook']->PreBookingRequest->PreBooking;
            $RoomDetail = $PreBooking->RoomDetails->RoomDetail;
	     $TermsAndConditions = $PreBooking->RoomDetails->RoomDetail->TermsAndConditions;
            $CancellationInformation = $PreBooking->CancellationInformations->CancellationInformation;
            $currency = $PreBooking->Currency;
            // $total += ceil($RoomDetail->TotalRate);
            $PreBookingDetails = $_item['prebook']->PreBookingDetails;
            $price = ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($PreBookingDetails->BookingAfterPrice, Auth::user()));
            $total += $price;
            // $members = $RoomDetail->Adults + $RoomDetail->Children;
            $adults = explode('|', $RoomDetail->Adults);
            $children = explode('|', $RoomDetail->Children);
            $room_types = explode('|', $RoomDetail->Type);
            $room_rate = explode('|', $RoomDetail->TotalRate);
            $items[] = [
                'type' => $room_types[0],
                'no' => $RoomDetail->TotalRooms,
                'rate' => ceil($room_rate[0]),
                'amount' => $price
            ];
            if (!is_array($CancellationInformation)) {
                $CancellationInformation = [$CancellationInformation];
            }
            // dd($CancellationInformation);
            // dd($PreBookingDetails);
        @endphp

        <h2 class="_checkout__headline">
            Booking Details - {{ $hotel->Name }}
            {{-- <strong class="text-success">{{ $PreBookingDetails->AgentCurrency }} {{ $price }}</strong> --}}
        </h2>

        @for ($x = 0; $x < $RoomDetail->TotalRooms; $x++)
            <div class="_checkout__group">

                @php
                    $members = $adults[$x] + $children[$x];
                @endphp

                <div class="_checkout__title"></div>

                <h5>Room #{{ $x + 1 }} - Booking Details</h5>

                @for ($i = 0; $i < $members; $i++)

                    <div class="_checkout__info">
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="cart[{{$key}}][{{$x}}][{{$i}}][salutation]" placeholder="Salutation" required />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cart[{{$key}}][{{$x}}][{{$i}}][first_name]" placeholder="FirstName" required />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="cart[{{$key}}][{{$x}}][{{$i}}][last_name]" placeholder="LastName" required />
                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" name="cart[{{$key}}][{{$x}}][{{$i}}][is_child]" />
                                <span>Is Child?</span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="cart[{{$key}}][{{$x}}][{{$i}}][age]" placeholder="Age" required />
                            </div>
                        </div>
                    </div>
                @endfor

            </div>
        @endfor

        <div class="_specialRequest">
            <div class="row">
                <div class="col-xs-12">
                    <label for="city" class="control-label">Special Request</label>
                    <textarea type="text" name="cart_special_requests[{{$key}}]" class="form-control"
                    placeholder="Type your request here &hellip;"></textarea>
                </div>
            </div>
        </div>

    @endforeach

    <h2 class="_checkout__headline">Booking Amount Breakup</h2>

    <table class="_checkout__table">
        <thead>
            <tr>
                <th>Room Type	</th>
                <th>No. of Rooms	</th>
                <th>Price</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $_item)
                <tr>
                    <td>{{ $_item['type'] }}</td>
                    <td>{{ $_item['no'] }}</td>
                    {{-- <td>{{ $currency . ' ' . ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_item['rate'], Auth::user())) }}</td> --}}
                    <td>{{ $currency . ' ' . $_item['amount'] / $_item['no'] }}</td>
                    <td>{{ $currency . ' ' . $_item['amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>Total Rate</td>
                <td>{{ $currency . ' ' . $total }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="_policy">
	 <h2 class="_policy__title">Terms And Conditions</h2>
         <p>{{$TermsAndConditions}}</p>
        <h2 class="_policy__title">Cancellation Policy *</h2>
        @foreach ($CancellationInformation as $_cancellation_information)
            @if (!empty($_cancellation_information->ChargeAmount))
                <p>
                    Charges -
                    <strong>{{ ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_cancellation_information->ChargeAmount, Auth::user())) }} {{ $_cancellation_information->Currency }}</strong>
                    Applicable If Cancelled After
                    <strong>{{ $_cancellation_information->StartDate }} Hrs</strong>
                </p>
                @if ($price == ceil($_cancellation_information->ChargeAmount))
                    <p class="text-danger">
                        <strong>This Booking Is Non-Refundable.</strong>
                    </p>
                @endif
            @endif
        @endforeach
        <p>
            General Remarks : Early checkout, No Show and Late amendments may result in entire stay charges. In case of date change or reduction/Increase in number of nights or change in occupancy, rates are subject to change. The cancellation deadline is calculated as per IST.
        </p>
        <p>
            If you agree to the cancellation policy, kindly select the check box and proceed.
        </p>
    </div>
@endif
