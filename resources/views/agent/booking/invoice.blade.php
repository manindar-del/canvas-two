@extends('layouts.agent')

@section('content')

<div class="_page">
    <div class="container">

		<a href="{{ route('booking.invoicepdf', [$booking->id, $booking_data->BookingDetails->BookingId]) }}" class="btn ticker-btn ">Download PDF</a>

        <br>

        <div class="pdf">

            <table width="100%" border="0" cellspacing="5" cellpadding="5" style="color: #000; font-family: Georgia, serif; font-size: 18px;">
                <tr>
                    <td width="200" align="left" valign="middle">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
                    </td>
                    <td valign="top">
                        <strong>CANVAS VACATIONS GLOBAL CO.,LTD</strong> <br>
                        <strong>Address :</strong> 165/156 MOO 5,Tambon Srisoonthorn Amphur,<br> Thalang District, Phuket 83100, Thailand
                        <strong>Email &nbsp;</strong>:online@canvasvacations.net<br>
                        <strong>Tel &nbsp;</strong>:+66-634538668 <br>
                        <strong>Global Support &nbsp;</strong>:+91-8101064166 <br>

                    </td>
                </tr>
            </table>

            <div style="margin: 15px 0; background-color:#87CEFA; color:#FFF; font-family: Gerogia, serif; font-size:32px; font-weight:bold; text-align:center;">
                <strong style="color: #fff;">PROFORMA INVOICE - ACCOMODATION</strong>
            </div>

            @if (!empty($booking->user))
                <div style="margin: 30px 0;">
                    <p><strong>To:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Tel:</strong> {{ $booking->user->tel }}</p>
                    <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                </div>
            @endif

            <table width="100%" class="tbl_1">
                <thead>
                    <tr>
                        <th style="text-align: left;">INVOICE DATE</th>
                        <th style="text-align: left;">INVOICE NO.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->created_at }}</td>
                        <td>{{ $booking_data->BookingDetails->BookingId }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin: 30px 0;">
				<table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
					<tr>
						<td width="200" align="left"><strong>Leading Guest</strong> </td>
						<td>:
							@php
							// dd($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests);
							if (is_array($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests)) {
								$dd = [];
								foreach ($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests as $_guest) {
									foreach ($_guest as $_guest_data) {
										$dd[] = $_guest_data;
									}
								}
							} else {
								$dd = $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest;
							}

							//print_r(($dd));
							if(is_array($dd) && !empty($dd)){
								foreach ($dd as $_gdata) {

								$guest =  $_gdata->LastName."/ ".$_gdata->FirstName." ".$_gdata->Salutation."."." &nbsp;&nbsp;";
								echo $guest;
								//echo $user_ids_string = implode(',',$_gdata->LastName);
								}
							}else{
								echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->LastName;
								echo " / ";
								echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->FirstName;
								echo " ";
								echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->Salutation;
								echo ". ";
							}

								@endphp
						</td>
					</tr>
					<tr>
						<td align="left"><strong>Travel Date</strong> </td>
						<td>:
							@php
								$arrival_date = $booking_data->BookingRequest->Booking->ArrivalDate;
								$ex = explode('/',$arrival_date);
								$day = $ex[0];
								$year = $ex[2];
								$month = date("M", strtotime($ex[2]));
								echo $day." ".$month." ".$year;
								$departure_date = $booking_data->BookingRequest->Booking->DepartureDate;
								$ex1 = explode('/',$departure_date);
								$day = $ex1[0];
								$year = $ex1[2];
								$month = date("M", strtotime($ex[2]));
								echo " - ".$day." ".$month." ".$year;
							@endphp
						</td>
					</tr>
				</table>
            </div>

			<table width="100%" class="tbl_1">
				<thead>
					<tr>
						<th align="center">Description</th>
						<th>Nights</th>
						<th>QTY</th>
						<th width="200">Total Price</th>
					</tr>
				</thead>
				<tr>
					<td valign="top" align="left"><p><strong>{{ $booking_data->BookingRequest->Booking->Name }} - {{ $hotel->hotel_address }}, {{ $hotel->country_code }}</strong> </p>
						<p> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Type}} x {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms}} Room(s)</p>
						<p>{{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Adults}} Adult(s), {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Children}} Child(ren)
						<p>
							Period of stay :
							@php
								$arrival_date = $booking_data->BookingRequest->Booking->ArrivalDate;
								$ex = explode('/',$arrival_date);
								$day = $ex[0];
								$year = $ex[2];
								$month = date("M", strtotime($ex[2]));
								echo $day." ".$month." ".$year;
								$departure_date = $booking_data->BookingRequest->Booking->DepartureDate;
								$ex1 = explode('/',$departure_date);
								//print_r($ex);
								$day = $ex1[0];
								$year = $ex1[2];
								$month = date("M", strtotime($ex[2]));
								echo " - ".$day." ".$month." ".$year;
							@endphp
						</p>
					</td>
					<td valign="top"  align="center">
						{{ (int)($booking_data->BookingRequest->Booking->DepartureDate)-
						(int)($booking_data->BookingRequest->Booking->ArrivalDate) }}
					</td>
					<td valign="top" align="center">
						{{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms}}
					</td>
					<td valign="top"  align="right">

						@php
							if (!empty($booking_data->hotel_hike)) {
								$_rate = explode('|', $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRate);
								$total_rate = ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_rate[0], Auth::user(), $booking_data->hotel_hike));
							} else {
								$total_rate = ceil($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRate);
							}
							$total_price_padded = $total_rate * $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms;
							$total_price = sprintf('%0.2f', $total_price_padded);
							echo $total_price;
							$payment_received =  $total_price;
							$outstanding_balance = 0.0;
						@endphp
					</td>
				</tr>
				<tr>
					<td colspan="3" align="left" valign="top">{{$booking_data->BookingRequest->Booking->Currency}} :
						@php
							echo strtoupper(\App\Helpers\CurrencyHelper::numberToWords($total_price_padded));
						@endphp
					</td>
					<td>
						<strong>TOTAL ({{$booking_data->BookingRequest->Booking->Currency}})</strong>
						<span style="float:right;">{{$total_price}}</span>
					</td>
				</tr>
			</table>

            <table width="100%" class="tbl_1" style="margin: 30px 0;">
                <tbody>
                    <tr>
                        <th style="width: 70%; background-color: transparent; text-align: left;">SERVICE CHARGE</th>
                        <td style="text-align: right;">100</td>
                    </tr>
                    <tr>
                        <th style="width: 70%; background-color: transparent; text-align: left;">GST ON SERVICE CHARGES (18%)</th>
                        <td style="text-align: right;">18</td>
                    </tr>
                </tbody>
            </table>

            <p style="margin: 30px 0;">
                The total billing amount is inclusive of Service Charge and Applicable GST.
                For detailed Invoice kindly contact accounts@canvasvacations.net
            </p>

            <div class="row" style="margin: 30px 0;">
                <div class="col-md-6 col-md-offset-6" align="right">
                    <table width="100%" border="0" cellspacing="5" cellpadding="5" class="tbl">
                        <tr>
                            <td><strong>Payment Received </strong></td>
                            <td><strong>{{$payment_received}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Outstanding Balance</strong></td>
                            <td><strong> 0.0</strong></td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL INR</strong></td>
                            <td><strong>{{ $total_price }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="color: #444;">
                <p>
                    Please note the ROE on the date of booking will be applicable.
                </p>
                <p>
                    Electronically generated. Does not require a physical signature. Bill payable immediately or subject to interest @24% per annum.
                </p>

                <p>Bank Details:</p>

                <ul style="padding-left: 30px; list-style-type: circle;">
                    <li>BENEFICIARY NAME : CANVAS VACATIONS GLOBAL CO., LTD.</li>
                    <li>BENEFICIARY ADDRESS : 165/156 MOO.5, SRISOONTHORN SUB-DISTRICT, THALANG DISTRICT, PHUKET 83110,  THAILAND</li>
                    <li>BENEFICIARY ACCOUNT NO : 045-3-66780-4</li>
                    <li>BANK NAME : KASIKORNBANK PUBLIC COMPANY LIMITED</li>
                    <li>Bank Address : 24/52 MOO.5NONG PRUE BANGLAMUNG CHON BURI 20120 THAILAND</li>
                    <li>BRANCH : NOEN PLAP WAN CHONBURI</li>
                    <li>SWIFT CODE : KASITHBK</li>
                </ul>
                <p>India Bank Details :</p>
                <ul style="padding-left: 30px; list-style-type: circle;">
                    <li>ACCOUNT NAME : CANVAS VACATIONS PRIVATE LIMITED.</li>
                    <li>BANK NAME : KOTAK MAHINDRA BANK LIMITED</li>
                    <li>ACCOUNT TYPE : CURRENT</li>
                    <li>ACCOUNT NUMBER :2513072291</li>
                    <li>IFSC CODE : KKBK0006576</li>
                    <li>BRANCH NAME : SALT LAKE KOLKATA</li>
                </ul>
                <p>
                    PLEASE ENSURE THAT CANVAS VACATIONS GETS FULL PAYMENT AND REMITTANCE CHARGES ARE BORNE BY REMITTER.ANY SHORT PAYMENT NEEDS TO BE PAID BEFORE CHECK IN
                </p>

                <p>
                    CANVAS VACATIONS PVT.LTD IS OPERATING AS COLLECTION AGENT FOR PAYMENTS IN INR. WITH SERVICE CHARGES .
                </p>
            </div>

        </div>

    </div>
</div>

@endsection

@push('header-bottom')
    <style>
        .tbl {
            border-collapse: collapse;
            width: 80%;
        }

        .tbl td,
        th {
            padding: 5px;
            color: #000;
        }

        .tbl_1 td,
        th {
            padding: 8px;
            margin: 10px;
            color: #000;
            border: 2px solid #000;
        }

        .tbl_1 th {
            background-color: #CCC;
            text-align: center;
            padding: 8px;
            margin: 10px;
        }

        .padleft20 {
            padding-left: 20px;
        }

        .ab_bgcolor {
            background-color: #87CEFA;
        }

        .header_bgcolor {
            background-color: #929292;
            color: #FFF;
            padding: 8px;
            margin-bottom: 15px;
        }

        .header_bgcolor h4 {
            color: #FFF;
            font-weight: bold;
        }

        strong {
            color: #000;
        }

        .black {
            color: #000;
            font-size: 16px;
        }

        .ol_1 li {
            list-style: decimal;
            color: #000;
        }

        .ol_1 {
            margin: 30px;
        }

        .ol_1 li {
            padding-left: 2em;
            text-indent: -2em;
        }

        .pdf b {
            color: #6E6E6E;
        }
    </style>
@endpush