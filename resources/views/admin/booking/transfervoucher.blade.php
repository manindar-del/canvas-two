@extends('layouts.admin')

@section('content')
@php
		//dd($cities);
			$transfer_date =$booking_data->form->form->transfer_date ;
			$transfer_date_new = date("d M Y", strtotime($transfer_date));
      // $nationality = $booking_data->form->form->nationality;
			$adult = $booking_data->form->form->adult;
			$child = $booking_data->form->form->child;
			// $infant = $booking_data->form->form->infant;
      $title = $booking_data->transfer->title;

      if(!empty($booking_data->transfer->address)){
        $address = $booking_data->transfer->address;
      }
      else {
        $address = "";
      }

      if(!empty($booking_data->transfer->phone)) {
        $phone = $booking_data->transfer->phone;
      }
      else {
        $phone = "";
      }

      $city_id = $booking_data->transfer->city_id;
      $country_id = $booking_data->transfer->country_id;
			$type = $booking_data->transfer->type;
			// $pick_up_time = $booking_data->transfer->pick_up_time;
			// $start_time = $booking_data->transfer->start_time;
			// $end_time = $booking_data->transfer->end_time;
			$adult_price = $booking_data->transfer->adult_price;
			$child_price = $booking_data->transfer->child_price;
			// $infant_price = $booking_data->transfer->infant_price;
			// $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
			$total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
@endphp

<div class="_page">

    <div class="container">
	<p>
		<a href="{{ route('bookingadmin.transfervoucherpdf', [$booking->id, $booking_data->BookingId]) }}" class="btn btn-danger">Download PDF</a>
	</p>
        <div class="pdf">
    <div class="row">
    <table width="100%" border="0" cellspacing="5" cellpadding="5" >
	<tr>
	  <td width="200" align="left"  valign="middle">
         <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
        </td>
	  <td  valign="top">
            <div class="col-xs-12"><h4><B>CANVAS VACATIONS CO.,LTD.</b></h4> </div>
            <div class="col-xs-12"><p><b>Address &nbsp;</b>: 222/63,Moo.13,The Grand Pattaya Village,Nong <br>Proe,Banglamung,Chonburi,Postal Code : 20150,Thailand</p></div>
            <div class="col-xs-6"><p><b>Tel &nbsp;</b>:+66-634538668</p> </div>
            <div class="col-xs-6"><p><b>Fax &nbsp;</b>:+66-33031058</p> </div>
            <div class="col-xs-12"><p><b>Email &nbsp;</b>:online@canvasvacations.net</p></div>
      </td>
      </tr>
      </table>
                <div class="black">
                  <div class="col-xs-12 ab_bgcolor text-center"><h3 style="color:#FFF;">CONFIRMATION -TRANSFERS</h3> </div>
                  <div class="clearfix">&nbsp;</div>
                  <div class="col-xs-12 header_bgcolor"><h4>TRANSFER INFORMATION</h4> </div>
                    <div class="col-xs-12">
                    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
                      {{-- <tr>
                          <td width="200"><strong>Title </strong></td>
                          <td>{{ $type }}</td>
                      </tr>
                      <tr>
                          <td><strong>Address </strong></td>
                          <td>{{$address}} {{ $city }}, {{ $country }}</td>
                      </tr>
                      <tr>
                          <td><strong>Tel</strong></td>
                          <td>{{$phone}}</td>
                      </tr> --}}
                      <tr>
                        <td><strong>Type</strong></td>
                        <td>{{ ucwords($type) }}</td>
                      </tr>
                      <tr>
                        <td><strong>City</strong></td>
                        <td>{{ $city }}</td>
                      </tr>
                      <tr>
                        <td width="200"><strong>Title </strong></td>
                        <td>{{ $title }}</td>
                      </tr>
                    </table>
                    </div>


                  <div class="col-xs-12 header_bgcolor"><h4>BOOKING DETAILS</h4> </div>

                 <div class="col-xs-12">
    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
      <tr>
        <td width="200"><strong>Transfer Date </strong></td>
        <td>{{$transfer_date_new}} </td>
        <td><strong>Voucher {{ hash('crc32', $booking_data->BookingId) }}</strong></td>
        <td></td>
      </tr>
      {{-- <tr>
        <td width="200"><strong>Transfer Date </strong></td>
        <td> {{$transfer_date_new}}</td>
        <td><strong>Voucher # </strong></td>
        <td></td>
      </tr> --}}
      {{-- <tr>
        <td><strong>Pick Up Time </strong> </td>
        <td> {{$pick_up_time}} </td>
        <td><strong>PNR # </strong></td>
        <td></td>
      </tr> --}}
      {{-- <tr>
        <td><strong>Duration </strong></td>
        <td> {{$start_time}} - {{$end_time}}</td>
        <td><strong>Agent Ref # </strong></td>
        <td></td>
      </tr> --}}
      {{-- <tr>
        <td><strong>Meal Plan </strong></td>
        <td></td>
        <td><strong>Confirmation Number # </strong></td>
        <td></td>
      </tr> --}}
      <tr>
        <td><strong>Number of Adult(s) </strong> </td>
        <td>{{$adult}}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><strong>Number of Child(ren) </strong></td>
        <td>{{$child}}  </td>
        <td></td>
        <td></td>
      </tr>
      {{-- <tr>
        <td><strong>Number of Infant(s) </strong> </td>
        <td>{{$infant}}</td>
        <td></td>
        <td></td>
      </tr> --}}

  </table>
</div>



                       <div class="col-xs-12 header_bgcolor"><h4>GUEST DETAILS</h4> </div>
                       <div class="col-xs-12">
    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
    {{-- <tr>
      <td width="200"><strong>Guest Nationality </strong></td>
      <td>
      @php
        $gcountry = DB::table('countries')->where('code', $nationality)->first();
        echo $gcountry->name;
      @endphp
       </td>
      <td></td>
    </tr> --}}
    <tr>
      {{-- <td><strong>Type # </strong> </td>
      <td>{{$type}}  </td> --}}
      <td>
        <strong>Name</strong>
        @foreach ($booking_data->members as $_guest_info)
					@foreach ($_guest_info as $_members)
            {{ $_members->first_name }}/
            {{ $_members->last_name }}
            {{ $_members->salutation }}.
					@endforeach
			  @endforeach
      </td>
    </tr>
  </table>
</div>

@if (!empty($booking_data->form->special_request))
  <div class="col-xs-12">
      <h4><i><strong>Special Request :</strong></i></h4>
  </div>
  <div class="col-xs-12">
      <p>{{ nl2br($booking_data->form->special_request) }}</p>
  </div>
@endif


<div class="col-xs-12"><h4><i><strong>Important Notes :</strong></i></h4> </div>
<div class="col-xs-12">
  <p>
    Reconfirm Pick Up and Drop Timings 24hrs prior to departure.
  </p>
  <p>
    Please cross check the voucher and pick up and drop timings also
    cross verify the Tour with Travel Dates for hassle free experience .
  </p>
  <p>
    Please wait at the hotel lobby or mentioned location 5 minutes
    before the pick time as in case if a tour is missed cannot be re-arranged.
  </p>
</div>

                    {{-- <div class="col-xs-12"><h4><i><strong>IMPORTANT NOTE TO HOTEL :</strong></i></h4> </div>
                <div class="col-xs-12"><p>This is a prepaid booking. Please do not collect any payment from the guest. Please contact our customer service center at +62 2949 5888 if you
have any question or doubt.</p> </div>

            <div class="col-xs-12"><h4><strong>Remark</strong></h4> </div>
             <div class="col-xs-12"><p>Bed type is not guarantee. It is subject to availability upon request.</p>
                <p>Extra bed is not allowed in standard room.</p> </div>

            <div class="col-xs-12"><h4><strong>Special Request</strong></h4> </div>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p> --}}

               {{-- <div class="col-xs-12" align="center">

               <table class="tbl">
               <tr>
                        <th colspan="3"> <div align="left"><h4><strong>Emergency Numbers</strong></h4> </div></th>
               </tr>
                    <tr>
                        <td><p><strong>Thailand </strong> <span class="padleft20">+66865706260</span> </p></td>
                        <td><p><strong> Hong Kong</strong><span class="padleft20"> +852 98043709</span></p></td>
                        <td><p><strong>  Macau </strong> <span class="padleft20">+853 66995729/+853 87973069</span></p></td>
                    </tr>
                </table>
             </div> --}}

             <div class="col-xs-12" align="center">
                <div align="left">
                  <h4><strong>Important Contacts : </strong></h4>
                </div>
                <table border="1" cellpadding="5" cellspacing="3" style="border-collapse:collapse; border-spacing: 0;">
                    <tr>
                        <td>
                            <p style="margin: 0; padding: 5px 15px;"><strong>Thailand Duty Manager : </strong> <span class="padleft20">+66-634538668</span></p>
                        </td>
                        {{-- <td><p><strong> Hong Kong</strong><span class="padleft20"> +852 98043709</span></p></td>
                        <td><p><strong>  Macau </strong> <span class="padleft20">+853 66995729/+853 87973069</span></p></td> --}}
                    </tr>
                </table>
              </div>

             {{-- <div class="clearfix">&nbsp;</div>

              <div class="col-xs-12 text-center"><p>INDIA - We are available 24 X 7 for on transfer support at +91 9582805803</p></div>
              <div class="clearfix">&nbsp;</div>

              </div>

              <div class="col-xs-4"> <p>Issued Date : {{date('d-M-Y g:i A', strtotime($created_at))}}</p></div>
              <div class="col-xs-4"><p>Issued By: </p></div> --}}

              <div class="col-xs-12" align="center">
                  <div align="left">
                    <h4><strong>Important Contacts : </strong></h4>
                    <p><span>Thailand Duty Manager : </span> <span class="padleft20">+66-634538668</span> </p>
                  </div>
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="col-xs-12 text-center">
                    <p>India On Tour Support : We are available 24x7 at +91-9582948053</p>
                </div>
                <div class="clearfix">&nbsp;</div>

              </div>
              {{-- <div class="col-xs-4"> <p>Issued Date : {{date('d-M-Y g:i A', strtotime($created_at))}}</p></div>
              <div class="col-xs-4"><p>Issued By: </p></div> --}}

    </div>
</div>

</div>

@endsection

@push('header-bottom')
    <style>
        /*  */
   .tbl  {
        border-collapse: collapse; width:80%;

    }

    .tbl  td{
        border: 3px solid #CCC;
        padding: 5px;
        color:#000;
    }
    .tbl_1 td,th {
        padding: 5px;
        color: #000;

		}
    .padleft20{
        padding-left:20px;
    }
    .ab_bgcolor{background-color:#87CEFA;}
    .header_bgcolor{background-color:#929292;color:#FFF;padding:8px; margin-bottom:15px;}
    .header_bgcolor h4{color:#FFF; font-weight:bold;}
    strong{color:#000;}
    .black{color:#000;font-size:16px;}
    .pdf b{color:#6E6E6E;}
    </style>
@endpush