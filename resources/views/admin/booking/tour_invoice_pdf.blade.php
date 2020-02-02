@php
	$tour_date =$booking_data->form->form->tour_date ;
	$tour_date_new = date("d M Y", strtotime($tour_date));
	$adult = $booking_data->form->form->adult;
	$child = $booking_data->form->form->child;
	$infant = $booking_data->form->form->infant;
	$title = $booking_data->tour->title;
	$type = $booking_data->tour->type;
	$pick_up_time = $booking_data->tour->pick_up_time;
	$start_time = $booking_data->tour->start_time;
	$end_time = $booking_data->tour->end_time;
	$adult_price = $booking_data->tour->adult_price;
	$child_price = $booking_data->tour->child_price;
	$infant_price = $booking_data->tour->infant_price;
	$total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
@endphp

<table width="100%" cellspacing="0" cellpadding="0" style="color: #000;">
	<tr>
		<td width="200" align="left" valign="middle">
			<img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
		</td>
		<td valign="top">
			<strong>CANVAS VACATIONS CO.,LTD.</strong> <br>
			<strong>Address :</strong> 222/63,Moo.13,The Grand Pattaya Village,Nong <br>
			Proe,Banglamung,Chonburi,Postal Code : 20150,Thailand <br>
			<strong>Tel &nbsp;</strong>:+66-634538668 <br>
			<strong>Fax &nbsp;</strong>:+66-33031058 <br>
			<strong>Email &nbsp;</strong>:online@canvasvacations.net
		</td>
	</tr>
</table>

<div style="margin: 15px 0; background-color:#87CEFA; color:#FFF; font-size:24px; font-weight:bold; text-align:center;">
	<strong style="color: #fff;">PROFORMA INVOICE - ACTIVITIES</strong>
</div>

@if (!empty($booking->user))
	<div style="margin: 30px 0;">
		<strong>To:</strong> {{ $booking->user->name }} <br />
		<strong>Tel:</strong> {{ $booking->user->tel }} <br />
		<strong>Email:</strong> {{ $booking->user->email }} <br />
	</div>
@endif

<table width="100%" class="tbl_1"  cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th style="text-align: left;">INVOICE DATE</th>
			<th style="text-align: left;">INVOICE NO.</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{ $booking->created_at }}</td>
			<td>{{ $booking_data->BookingId }}</td>
		</tr>
	</tbody>
</table>

<div style="margin: 30px 0;">
	<table width="100%" cellspacing="0" cellpadding="0" class="tbl_1">
		<tr>
			<td width="200" align="left">
				<p><strong>Leading Guest</strong></p>
			</td>
			<td>
				@foreach ($booking_data->members as $_guest_info)
					<p>
						@foreach ($booking_data->members as $_guest_info)
							@foreach ($_guest_info as $_members)
								{{ $_members->first_name }}/
								{{ $_members->last_name }}
								{{ $_members->salutation }}.
							@endforeach
						@endforeach
					</p>
				@endforeach
			</td>
		</tr>
		<tr>
			<td align="left"><strong>Tour Date</strong> </td>
			<td>: {{$tour_date_new}}</td>
		</tr>
	</table>
</div>

<table width="100%" cellspacing="0" cellpadding="0" class="tbl_1">
	<thead>
		<tr>
			<th align="center">Description</th>
			<th>Type</th>
			<th>Price	</th>
			<th width="200">Total Price</th>
		</tr>
	</thead>
	<tr>
		<td valign="top" align="left">
			<p><strong>{{ $title }} </strong> </p>
			<p>Pick Up Time : {{$pick_up_time}}</p>
			<p>Duration : {{$start_time}} - {{$end_time}}</p>
			<p>
				@php
					if($adult) echo $adult. " Adult(s), ";
					if($child) echo $child. " Child(ren), ";
					if($infant) echo $infant. " Infant(s) ";
				@endphp
			</p>
		</td>
		<td valign="top"  align="center">{{$type}}</td>
		<td valign="top" align="center">
			<p>
				@if(!empty($adult_price))
				Adult : {{$adult_price}} X {{$adult}} Adult(s) = {{ $adult_price * $adult }}
				@endif
			</p>
			<p>
				@if(!empty($child_price))
				Adult : {{$child_price}} X {{$child}} Child(ren) = {{ $child_price * $child }}
				@endif
			</p>
			<p>
				@if(!empty($infant_price))
				Adult : {{$infant_price}} X {{$infant}} Infant(s) = {{ $infant_price * $infant }}
				@endif
			</p>
		</td>
		<td valign="top"  align="right">
			@php
				$total_price_padded = ceil($total_tour_rate);
				$total_price = sprintf('%0.2f', $total_price_padded);
				echo $total_price;
				$payment_received =  $total_price;
				$outstanding_balance = 0.0;
			@endphp
		</td>
	</tr>
	<tr>
		<td colspan="3" align="left" valign="top">INR :
			@php
				echo strtoupper(\App\Helpers\CurrencyHelper::numberToWords($total_price_padded));
			@endphp
		</td>
		<td>
			<strong>TOTAL INR</strong>
			<span style="float:right;">{{ $total_price}}</span>
		</td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" class="tbl_1" style="margin: 30px 0;">
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
		<table width="100%" border="0"  cellspacing="0" cellpadding="0" class="tbl_1">
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
		<li>BENEFICIARY NAME : CANVAS VACATIONS CO., LTD.</li>
		<li>BENEFICIARY ADDRESS : 109/18 MOO.6, SOI SIAM COUNTRY CLUB</li>
		<li>NONG PROE, BANGLAMUNG, CHON BURI 20150 THAILAND</li>
		<li>BENEFICIARY ACCOUNT NO : 045-3-66780-4</li>
		<li>BANK NAME : KASIKORNBANK PUBLIC COMPANY LIMITED</li>
		<li>Bank Address : 24/52 MOO.5NONG PRUE BANGLAMUNG CHON BURI 20120 THAILAND</li>
		<li>BRANCH : NOEN PLAP WAN CHONBURI</li>
		<li>SWIFT CODE : KASITHBK</li>
	</ul>

	<p>
		PLEASE ENSURE THAT CANVAS VACATIONS GETS FULL PAYMENT AND REMITTANCE CHARGES ARE BORNE BY REMITTER.ANY SHORT PAYMENT NEEDS TO BE PAID BEFORE CHECK IN
	</p>

	<p>
		CANVAS VACATIONS PVT.LTD IS OPERATING AS COLLECTION AGENT FOR PAYMENTS IN INR. WITH SERVICE CHARGES .
	</p>
</div>

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
		border: 1px solid #000;
		border-spacing: 0;
		border-collapse: collapse;
	}

    .tbl_1,
    .tbl_1 th,
    .tbl_1 td {
        font-size: 13px;
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