@extends('layouts.agent')

@section('content')

	<div class="container">
	<div class="col-xs-12">
	<p>
		<a href="{{ route('booking.invoicepdf', [$booking->id, $booking_data->BookingDetails->BookingId]) }}" class="btn ticker-btn ">Download PDF</a>
	</p>
</div>
		<div class="pdf">
	<div class="row">
	<table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
	<tr>
	  <td width="200" align="left"  valign="top">
			<img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
		</td>
	  <td  valign="top"><div class="col-xs-12"><h4><b>{{ config('app.name') }}</b></h4> </div>
			   <div class="col-xs-12"><p><b>Address</b> :RDB Boulevard, Plot K-1, Sector V, Block EP & GP, Salt Lake City, 8th Floor, Kolkata, West Bengal 700091</p></div>
				 <div class="col-xs-6"><p><b>Tel</b> : 91 7699002674</p> </div>
				<div class="col-xs-6"><p><b>Fax</b> : 0343 2606733</p> </div>
				 <div class="col-xs-12"><p><b>Email</b>  : tradesupport@breakbag.com</p></div>
				 <div class="col-xs-6"><p><b>Co Reg No.</b> : 1234567890</p> </div>
				<div class="col-xs-6"><p><b>VAT Reg No.</b> : 1234567890</p> </div>
			   <div class="clearfix">&nbsp;</div></td>
	</tr>
	</table>

				  <div class="col-xs-12"><h3 style="text-align:center;text-decoration: underline;padding-bottom:10px;"><strong>PROFORMA INVOICE</strong></h3> </div>

				  <div class="col-xs-6">
 <table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
	<tr>
	  <td width="200" align="left"><strong>To</strong> </td>
	  <td>:<strong>{{$user->name}}</strong> </td>
	</tr>
	<tr>
	  <td><strong>Tel</strong> </td>
	  <td> :{{$user->tel}}</td>
	</tr>
	<tr>
	  <td><strong>Fax</strong> </td>
	  <td> :0000 00000</td>
	</tr>
	<tr>
	  <td><strong>Email</strong> </td>
	  <td> :{{$user->email}}</td>
	</tr>
	<tr>
	  <td><strong>Address</strong> </td>
	  <td> <p>:{{$user->address}}</p> </td>
	</tr>
  </table>
</div>
<div class="col-xs-6">
  <table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
	<tr>
	  <td width="200" align="left"><strong>No </strong></td>
	  <td> :{{$booking_data->BookingDetails->BookingId}}</td>
	</tr>
	<tr>
	  <td><strong>Issued Date</strong> </td>
	  <td>:{{date('d M Y', strtotime($created_at))}} </td>
	</tr>
	<tr>
	  <td><strong>Booking No.</strong> </td>
	  <td>:{{$booking_data->BookingDetails->BookingCode}} </td>
	</tr>
	<tr>
	  <td><strong>Your Ref</strong> </td>
	  <td>:HOLIDAYS </td>
	</tr>
	<tr>
	  <td><strong>Credit Term </strong></td>
	  <td>:Pre-Payment </td>
	</tr>
	<tr>
	  <td><strong>Due Date</strong> </td>
	  <td>:03 Sep 2018 </td>
	</tr>
	<tr>
	  <td><strong>Total Page</strong> </td>
	  <td>:1 </td>
	</tr>
	<tr>
	  <td><strong>Issue By</strong> </td>
	  <td>: breakbag</td>
	</tr>
  </table>
</div>

<div class="col-xs-12">
<div style="border-top:4px solid #000; padding:5px;border-bottom:1px solid #000;">
<table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
  <tr>
	<td width="200" align="left"><strong>Leading Guest</strong> </td>
	<td>:
		@php
		$dd = $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest;
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
		//print_r($ex);
		 $day = $ex1[0];
		$year = $ex1[2];
	 $month = date("M", strtotime($ex[2]));
	echo " - ".$day." ".$month." ".$year;

	@endphp

</td>
  </tr>
</table>
 </div>
</div>
<div class="clearfix">&nbsp;</div>
 <div class="col-xs-12">
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
	<p>Period of stay :

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
	  </p>
	 </td>
	<td valign="top"  align="center">{{ (int)($booking_data->BookingRequest->Booking->DepartureDate)-
						(int)($booking_data->BookingRequest->Booking->ArrivalDate) }}</td>
	<td valign="top" align="center">{{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms}}</td>
	<td valign="top"  align="right">

@php
$total_price_padded = ceil($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRate) * ($booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms);
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
	<td><strong>TOTAL ({{$booking_data->BookingRequest->Booking->Currency}})</strong> <span style="float:right;">{{$total_price}}</span></td>

  </tr>
</table> <div>&nbsp;</div>
 </div>

 <div class="col-xs-6">

  </div>
 <div class="col-xs-6" align="right">
 <table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
  <tr>
<td><strong>Payment Received </strong></td>
	<td>{{$payment_received}}</td>
  </tr>
<td><strong>Outstanding Balance</strong></td>
	<td><strong> 0.0</strong></td>
  </tr>

<td><strong>TOTAL ({{$booking_data->BookingRequest->Booking->Currency}}) </strong></td>
	<td><strong>{{ $total_price}}</strong></td>
</tr>
</table>
 </div>
 <div class="col-xs-12">
  <p style="text-decoration: underline;">Remark</p>
  <ol class="ol_1">
<li> ADD USD 25 or Equivalent amount (in case of other currency) in each remittance for covering banking Charge.</li>
<li> In Remittance 71A Detail of Charges should be OUR.</li>
<li> Please pay the amount before the payment due date to avoid auto release of booking. Bank details are mentioned above.</li>
<li> Payment/Remittance to be made in the currency as stated in the invoice.</li>
<li> To ensure prompt credit, please send payment details along with remittance advice at prepay@travelbullz.com.</li>
<li> Interest @ 18% will be charged on all overdue.</li>
<li> This document is not a voucher. Voucher will be issued after the receipt of full monies &amp; necessary documents.</li>

</ol>
  </div>

  <div class="col-xs-12">
  <p style="text-decoration: underline;">Payable to</p>
  <table width="100%" border="0" cellspacing="5" cellpadding="5"class="tbl">
	<tr>
	  <td width="200" align="left"><strong>Bank</strong> </td>
	  <td>: HSBC BANK </td>
	</tr>
	<tr>
	  <td><strong>Branch</strong> </td>
	  <td> :1 Queen􀂶s Road Central, Hong Kong</td>
	</tr>
	<tr>
	  <td><strong>A/C No.</strong> </td>
	  <td> :801-148032-838 (USD/SGD/GBP/EUR/HKD/CNY/AUD/NZD/</td>
	</tr>
	<tr>
	  <td><strong>Swift</strong> </td>
	  <td> :HSBCHKHHHKH</td>
	</tr>
	<tr>
	  <td><strong>Address</strong> </td>
	  <td> :RDB Boulevard, Plot K-1, Sector V, Block EP & GP, Salt Lake City,
		8th Floor, Kolkata, West Bengal 700091 WEST BENGAL </td>
	</tr>

	 <tr>
	  <td><strong>Beneficiary Name </strong></td>
	  <td> :HSBCHKHHHKH</td>
	</tr>
  </table>
  </div>

<div class="col-xs-12">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p style="border-bottom:4px solid #CCC;">&nbsp;</p>
  <p>This is a computer generated invoice. No Signature is required.Cheque must be crossed and made payable to TravelBullz (HK) Limited Bank fees/charges for payments
made via TT will not be borne by TravelBullz (HK) Limited
</p>
<p>Reprint on 31/08/2018 19:17 By</p>
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
			padding: 8px;  margin:10px;
			color: #000;
			border: 2px solid #000;
		}

		.tbl_1  th {
			background-color: #CCC; text-align:center;padding: 8px;  margin:10px;
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
	.pdf	b{color:#6E6E6E;}
	</style>
@endpush