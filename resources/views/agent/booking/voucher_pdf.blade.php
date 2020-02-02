
    <table width="100%" border="0" cellspacing="5" cellpadding="5" >
	<tr>
	  <td width="200" align="left"  valign="middle">
         <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
        </td>
	  <td  valign="top">
            <div class="col-xs-12"><h4><B>CANVAS VACATIONS CO.,LTD.</b></h4> </div>
            <div class="col-xs-12"><p><b>Address &nbsp;</b>:222/63,Moo.13,The Grand Pattaya Village,Nong <br>Proe,Banglamung,Chonburi,Postal Code : 20150,Thailand</p></div>
            <div class="col-xs-6"><p><b>Tel &nbsp;</b>:+66-634538668</p> </div>
            <div class="col-xs-6"><p><b>Fax &nbsp;</b>:+66-33031058</p> </div>
            <div class="col-xs-12"><p><b>Email &nbsp;</b>:online@canvasvacations.net</p></div>
      </td>
      </tr>
      </table>
                <div class="black">
                  <div style="background-color:#87CEFA;text-align:center;padding:2px;color:#FFF;font-size:24px; font-weight:bold;">CONFIRMATION - ACCOMMODATION</div>
                  <div class="clearfix">&nbsp;</div>
                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">HOTEL INFORMATION </div>
                    <div class="col-xs-12">
                    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
                    <tr>
                        <td width="200"><strong>Hotel Name </strong></td>
                        <td>{{ $booking_data->BookingRequest->Booking->Name }}, {{ $hotel->country_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address </strong></td>
                        <td> {{ $hotel->hotel_address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tel</strong></td>
                        <td></td>
                    </tr>
                    </table>
                    </div>


                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">BOOKING DETAILS </div>

                 <div class="col-xs-12">
     <table width="100%"  cellspacing="2" cellpadding="5">
    <tr>
    <td width="200"><strong>Check in Date </strong></td>
      <td>
      @php
		$arrival_date = $booking_data->BookingRequest->Booking->ArrivalDate;
		$ex = explode('/',$arrival_date);
		 $day = $ex[0];
		$year = $ex[2];
	 $month = date("M", strtotime($ex[2]));
	echo $day." ".$month." ".$year;
	@endphp
      </td>
      <td>
        <strong>Voucher # </strong>
        @if (!empty($booking_data->BookingDetails->BookingId))
          {{ hash('crc32', $booking_data->BookingDetails->BookingId) }}
        @endif
      </td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Check out Date </strong> </td>
      <td>
      @php

        $departure_date = $booking_data->BookingRequest->Booking->DepartureDate;
		$ex1 = explode('/',$departure_date);
		//print_r($ex);
		 $day = $ex1[0];
		$year = $ex1[2];
	 $month = date("M", strtotime($ex[2]));
	echo $day." ".$month." ".$year;

	@endphp
       </td>
      {{-- <td><strong>PNR # </strong></td> --}}
      <td></td>
    </tr>
    <tr>
      <td><strong>No. of Night(s) </strong></td>
      <td>
        {{-- {{
          (int)($booking_data->BookingRequest->Booking->DepartureDate)-
          (int)($booking_data->BookingRequest->Booking->ArrivalDate)
        }} --}}
        @php
          $earlier = explode('/', $booking_data->BookingRequest->Booking->ArrivalDate);
          $earlier = $earlier[2] . '-' . $earlier[1] . '-' . $earlier[0];
          $earlier = new DateTime($earlier);
          $later = explode('/', $booking_data->BookingRequest->Booking->DepartureDate);
          $later = $later[2] . '-' . $later[1] . '-' . $later[0];
          $later = new DateTime($later);
          $diff = $later->diff($earlier)->format("%a");
          echo $diff;
        @endphp
      </td>
      {{-- <td><strong>Agent Ref # </strong></td> --}}
      <td></td>
    </tr>
    <tr>
      <td><strong>Meal Plan </strong></td>
      <td></td>
      {{-- <td><strong>Confirmation Number # </strong></td> --}}
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Room(s) </strong></td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Adult(s) </strong> </td>
      <td>{{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Adults}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Child(ren) </strong></td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Children}} </td>
      <td></td>
      <td></td>
    </tr>
  </table>
</div>



                       <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">GUEST DETAILS </div>
                       <div class="col-xs-12">
    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
    <tr>
      <td width="200"><strong>Guest Nationality </strong></td>
      <td> @php
        $cID = $booking_data->BookingRequest->Booking->GuestNationality;
        $gcountry = DB::table('countries')->where('code', $cID)->first();
        echo $gcountry->name;
        @endphp </td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Room # </strong> </td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Type}} </td>
      <td> @php
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
        $guest =  $_gdata->LastName." / ".$_gdata->FirstName." ".$_gdata->Salutation."."." &nbsp;&nbsp;";
        echo $guest;

        }
        }else{
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->LastName;
        echo " / ";
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->FirstName;
        echo " ";
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->Salutation;
        echo ". ";
        }

        @endphp </td>
    </tr>
  </table>
</div>

@if (!empty($booking_data->special_request) && is_string($booking_data->special_request))
  <div class="col-xs-12">
      <h4><i><strong>Special Request :</strong></i></h4>
  </div>
  <div class="col-xs-12">
      <p>{{ nl2br($booking_data->special_request) }}</p>
  </div>
@endif

<div class="col-xs-12">
    <h4><i><strong>Important Notes :</strong></i></h4>
  </div>

  <div class="col-xs-12">
    <p>
      International Check In :14:00 hrs and International Check
      Out :12:00.Early Check In and Late Check Out subject to hotel
      discretion and may be chargeable at times by the hotel.
    </p>
    <p>
      City Tax, Bed Tax, Environmental tax,Visa Verification Tax,
      Resort Fees, Surcharge on festive periods and all other such
      taxes are not included. These taxes varies country to country
      and traveler have to pay on arrival. We cannot collect these taxes.
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

<div class="col-xs-12">
  <h4><strong>Important Contacts : </strong></h4>
  <p>Thailand Duty Manager : +66-634538668</p>
</div>

<div class="clearfix">&nbsp;</div>
<div class="col-xs-12 text-center">
  <p style="margin-bottom: 0 auto; text-align: center;">India On Tour Support : We are available 24x7 at +91-9582948053</p>
</div>
{{-- <div class="clearfix">&nbsp;</div> --}}

</div>

@php
$ibookings = DB::table('bookings')->where('id', $id)->first();
$created_at =  $ibookings->created_at;
// $createdAt= $created_at->format('d-M-Y');
@endphp

{{-- <div class="col-xs-4"> <p>Issued Date : {{date('d-M-Y g:i A', strtotime($created_at))}}</p></div>
<div class="col-xs-4"><p>Issued By: </p></div> --}}

