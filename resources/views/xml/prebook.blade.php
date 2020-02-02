<PreBookingRequest>
	<Authentication>
		<AgentCode>{{ config('app.agent.code') }}</AgentCode>
		<UserName>{{ config('app.agent.user_name') }}</UserName>
		<Password>{{ config('app.agent.password') }}</Password>
	</Authentication>
  <PreBooking>
	  <SearchSessionId>{{$json_data->SearchSessionId}}</SearchSessionId>
		<ArrivalDate>{{ $form['check_in'] }}</ArrivalDate>
		<DepartureDate>{{ $form['check_out'] }}</DepartureDate>
		<GuestNationality>{{ $form['nationality'] }}</GuestNationality>
		<CountryCode>{{ $form['country'] }}</CountryCode>
		<City>{{ $form['city'] }}</City>
		<HotelId>{{ $hotel->Id }}</HotelId>
	         <Name>{{ $hotel->hotel->name }}</Name>
	         <Address>{{ $hotel->hotel->hotel_address }}</Address>
		<Currency>{{ $form['currency'] }}</Currency>
    <RoomDetails>
      <RoomDetail>
				<Type>{{ $room->Type }}</Type>
				<BookingKey>{{ $room->BookingKey }}</BookingKey>
				<Adults>{{ $room->Adults }}</Adults>
        <Children>{{ $room->Children }}</Children>
				@if ($room->Children && !empty($room->ChildrenAges))
					<ChildrenAges>{{ $room->ChildrenAges }}</ChildrenAges>
				@else
        	<ChildrenAges>0</ChildrenAges>
				@endif
        <TotalRooms>{{ $form['rooms'] }}</TotalRooms>
				<TotalRate>{{ $room->TotalRate }}</TotalRate>
        <TermsAndConditions/>
      </RoomDetail>
    </RoomDetails>
  </PreBooking>
</PreBookingRequest>
