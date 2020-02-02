<CancellationPolicyRequest>
	<Authentication>
		<AgentCode>{{ config('app.agent.code') }}</AgentCode>
		<UserName>{{ config('app.agent.user_name') }}</UserName>
		<Password>{{ config('app.agent.password') }}</Password>
	</Authentication>
  <HotelId>{{ $hotel->Id }}</HotelId>
	<ArrivalDate>{{ $form['check_in'] }}</ArrivalDate>
	<DepartureDate>{{ $form['check_out'] }}</DepartureDate>
	<CountryCode>{{ $form['country'] }}</CountryCode>
	<City>{{ $form['city'] }}</City>
  <GuestNationality>{{ $form['nationality'] }}</GuestNationality>
  <Currency>{{ $form['currency'] }}</Currency>
  <RoomDetails>
    <RoomDetail>
			<BookingKey>{{ $room->BookingKey }}</BookingKey>
			{{-- <NoOfAdults>{{ $form['adult'] }}</NoOfAdults> --}}
			<NoOfAdults>{{ $room->Adults }}</NoOfAdults>
			{{-- <NoOfChilds>{{ $form['child'] }}</NoOfChilds> --}}
			<NoOfChilds>{{ $room->Children }}</NoOfChilds>
			@if ($room->Children)
				<ChildrenAges>{{ $room->ChildrenAges }}</ChildrenAges>
			@endif
			<Type>{{ $room->Type }}</Type>
    </RoomDetail>
  </RoomDetails>
</CancellationPolicyRequest>
