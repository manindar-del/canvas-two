@php

			$tour_date =$booking_data->form->form->tour_date ;
			$tour_date_new = date("d M Y", strtotime($tour_date));
      $nationality = $booking_data->form->form->nationality;
			$adult = $booking_data->form->form->adult;
			$child = $booking_data->form->form->child;
			$infant = $booking_data->form->form->infant;
			$title = $booking_data->tour->title;
      if(!empty($booking_data->tour->address)) $address = $booking_data->tour->address;
      else $address = "";
      if(!empty($booking_data->tour->phone)) $phone = $booking_data->tour->phone;
      else $phone = "";
      $city_id = $booking_data->tour->city_id;
      $country_id = $booking_data->tour->country_id;
			$type = $booking_data->tour->type;
			$pick_up_time = $booking_data->tour->pick_up_time;
			$start_time = $booking_data->tour->start_time;
			$end_time = $booking_data->tour->end_time;
			$adult_price = $booking_data->tour->adult_price;
			$child_price = $booking_data->tour->child_price;
			$infant_price = $booking_data->tour->infant_price;
			$total_tour_rate = ($adult * $adult_price) +  ($child * $child_price) + ($infant * $infant_price);
@endphp
    <table width="100%" border="0" cellspacing="5" cellpadding="5" >
	<tr>
	  <td width="200" align="left"  valign="middle">
         <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
        </td>
	  <td  valign="top">
            <div class="col-xs-12"><h4><B>CANVAS VACATIONS CO.,LTD.</b></h4> </div>
            <div class="col-xs-12"><p><b>222/63,Moo.13,The Grand Pattaya Village,Nong <br>Proe,Banglamung,Chonburi,Postal Code : 20150,Thailand</p></div>
            <div class="col-xs-6"><p><b>Tel &nbsp;</b>:+66-634538668</p> </div>
            <div class="col-xs-6"><p><b>Fax &nbsp;</b>:+66-33031058</p> </div>
            <div class="col-xs-12"><p><b>Email &nbsp;</b>:online@canvasvacations.net</p></div>
      </td>
      </tr>
      </table>
                <div class="black">
                  <div style="background-color:#87CEFA;text-align:center;padding:2px;color:#FFF;font-size:24px; font-weight:bold;">CONFIRMATION – ACTIVITIES</div>
                  <div class="clearfix">&nbsp;</div>
                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">TOUR INFORMATION </div>
                    <div class="col-xs-12">
                    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
                      <tr>
                        <td width="200"><strong>Title </strong></td>
                        <td>{{ $title }}</td>
                      </tr>
                      <tr>
                        <td><strong>City </strong></td>
                        <td>{{ $city->name }}</td>
                      </tr>
                      {{-- <tr>
                        <td><strong>Tel</strong></td>
                        <td>{{$phone}}</td>
                      </tr> --}}
                    </table>
                    </div>


                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">BOOKING DETAILS </div>

                 <div class="col-xs-12">
     <table width="100%"  cellspacing="2" cellpadding="5">
    <tr>
    <td width="200"><strong>Tour Date </strong></td>
      <td>{{$tour_date_new}} </td>
      <td><strong>Voucher # </strong> {{ hash('crc32', $booking_data->BookingId) }}</td>
      <td></td>
    </tr>
    {{-- <tr>
      <td><strong>Pick Up Time </strong> </td>
      <td>{{$pick_up_time}}</td>
      <td><strong>PNR # </strong></td>
      <td></td>
    </tr> --}}
    {{-- <tr>
      <td><strong>Duration </strong></td>
      <td>{{$start_time}} - {{$end_time}}</td>
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
      <td><strong>Tour Starting At</strong></td>
      <td>{{$start_time}}</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Adult(s)  </strong></td>
      <td> {{$adult}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Child(ren) </strong> </td>
      <td>{{$child}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Infant(s) </strong></td>
      <td> {{$infant}} </td>
      <td></td>
      <td></td>
    </tr>
  </table>
</div>



                       <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">GUEST DETAILS </div>
                       <div class="col-xs-12">
    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
    <tr>
      <td width="200">
        <strong>Guest Nationality </strong>
        @php
          $gcountry = DB::table('countries')->where('code', $nationality)->first();
          echo $gcountry->name;
        @endphp
      </td>
    </tr>
    <tr>
      {{-- <td><strong>Type # </strong> </td>
      <td> {{$type}} </td> --}}
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

@if (!empty($booking_data->form->special_request) && is_string($booking_data->form->special_request))
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

            {{-- <div class="col-xs-12"><h4><strong>Remark</strong></h4> </div>
             <div class="col-xs-12"><p>Bed type is not guarantee. It is subject to availability upon request.</p>
                <p>Extra bed is not allowed in standard room.</p> </div>

            <div class="col-xs-12"><h4><strong>Special Request</strong></h4> </div>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p> --}}

              <div class="col-xs-12" align="center">
                <div align="left">
                  <h4><strong>Important Contacts : </strong></h4>
                  <p><span>Thailand Duty Manager : </span> <span class="padleft20">+66-634538668</span> </p>
                </div>
              </div>

             <div class="clearfix">&nbsp;</div>
              <div class="col-xs-12 text-center"><p style="text-align: center;">India On Tour Support : We are available 24x7 at +91-9582948053</p></div>
              <div class="clearfix">&nbsp;</div>

              </div>
              {{-- <div class="col-xs-4"> <p>Issued Date : {{date('d-M-Y g:i A', strtotime($created_at))}}</p></div>
              <div class="col-xs-4"><p>Issued By: </p></div> --}}

