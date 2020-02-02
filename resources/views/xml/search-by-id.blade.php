<HotelFindRequest>
  <Authentication>
    <AgentCode>{{ config('app.agent.code') }}</AgentCode>
    <UserName>{{ config('app.agent.user_name') }}</UserName>
    <Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
  <Booking>
		<ArrivalDate>01/10/2014</ArrivalDate>
		<DepartureDate>03/10/2014</DepartureDate>
		<CountryCode>AE</CountryCode>
		<City>GAE9</City>
		<HotelIDs>
			<Int>150884</Int>
			{{-- <Int>171888</Int>
			<Int>245325</Int>
			<Int>151754</Int>
			<Int>248860</Int> --}}
		</HotelIDs>
		<GuestNationality>IN</GuestNationality>
		<Rooms>
			<Room>
				<Type>Room-1</Type>
				<NoOfAdults>{{ $adult }}</NoOfAdults>
				<NoOfChilds>{{ $child }}</NoOfChilds>
				<ChildrenAges>
					<ChildAge>4</ChildAge>
				</ChildrenAges>
			</Room>
		</Rooms>
  </Booking>
</HotelFindRequest>
