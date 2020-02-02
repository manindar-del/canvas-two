{{-- <BookingRequest>
  <Authentication>
    <AgentCode>{{ config('app.agent.code') }}</AgentCode>
    <UserName>{{ config('app.agent.user_name') }}</UserName>
    <Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
  <Booking>
    <ArrivalDate>{{ $PreBooking->ArrivalDate }}</ArrivalDate>
    <DepartureDate>{{ $PreBooking->DepartureDate }}</DepartureDate>
    <GuestNationality>{{ $PreBooking->GuestNationality }}</GuestNationality>
    <CountryCode>{{ $PreBooking->CountryCode }}</CountryCode>
    <City>{{ $PreBooking->City }}</City>
    <HotelId>{{ $PreBooking->HotelId }}</HotelId>
    <Name>{{ $hotel->Name }}</Name>
    <Address>{{ $hotel->hotel->hotel_address }}</Address>
    <Currency>{{ $PreBooking->Currency }}</Currency>
    <RoomDetails>
      <RoomDetail>
        <Type>{{ $RoomDetail->Type }}</Type>
        <BookingKey>{{ $RoomDetail->BookingKey }}</BookingKey>
        <Adults>{{ $RoomDetail->Adults }}</Adults>
        <Children>{{ $RoomDetail->Children }}</Children>
        <ChildrenAges>{{ $RoomDetail->ChildrenAges }}</ChildrenAges>
        <TotalRooms>{{ $RoomDetail->TotalRooms }}</TotalRooms>
        <Rooms>{{ $RoomDetail->TotalRooms }}</Rooms>
        <TotalRate>{{ $RoomDetail->TotalRate }}</TotalRate>
        <Guests>
          @foreach ($members as $_member)
            <Guest>
              <Salutation>{{ $_member['salutation'] }}</Salutation>
              <FirstName>{{ $_member['first_name'] }}</FirstName>
              <LastName>{{ $_member['last_name'] }}</LastName>
              <Age>{{ $_member['age'] }}</Age>
            </Guest>
          @endforeach
        </Guests>
      </RoomDetail>
    </RoomDetails>
  </Booking>
</BookingRequest> --}}

<BookingRequest>
  <Authentication>
    <AgentCode>{{ config('app.agent.code') }}</AgentCode>
    <UserName>{{ config('app.agent.user_name') }}</UserName>
    <Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
  <Booking>
    <SearchSessionId>{{ $PreBooking->SearchSessionId }}</SearchSessionId>
    <ArrivalDate>{{ $PreBooking->ArrivalDate }}</ArrivalDate>
    <DepartureDate>{{ $PreBooking->DepartureDate }}</DepartureDate>
    <GuestNationality>{{ $PreBooking->GuestNationality }}</GuestNationality>
    <CountryCode>{{ $PreBooking->CountryCode }}</CountryCode>
    <City>{{ $PreBooking->City }}</City>
    <HotelId>{{ $PreBooking->HotelId }}</HotelId>
    <Name>{{ $hotel->Name }}</Name>
    <Currency>{{ $PreBooking->Currency }}</Currency>
    <RoomDetails>
      <RoomDetail>
        <Type>{{ $RoomDetail->Type }}</Type>
        <BookingKey>{{ $RoomDetail->BookingKey }}</BookingKey>
        <Adults>{{ $RoomDetail->Adults }}</Adults>
        <Children>{{ $RoomDetail->Children }}</Children>
        <ChildrenAges>{{ $RoomDetail->ChildrenAges }}</ChildrenAges>
        <TotalRooms>{{ $RoomDetail->TotalRooms }}</TotalRooms>
        <TotalRate>{{ $RoomDetail->TotalRate }}</TotalRate>
        @foreach ($members as $_member_group)
          <Guests>
            @foreach ($_member_group as $_member)
              <Guest>
                <Salutation>{{ $_member['salutation'] }}</Salutation>
                <FirstName>{{ $_member['first_name'] }}</FirstName>
                <LastName>{{ $_member['last_name'] }}</LastName>
                <Age>{{ $_member['age'] }}</Age>
              </Guest>
            @endforeach
          </Guests>
        @endforeach
      </RoomDetail>
    </RoomDetails>
  </Booking>
</BookingRequest>
