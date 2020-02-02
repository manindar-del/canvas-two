<HotelFindRequest>
  <Authentication>
    <AgentCode>{{ config('app.agent.code') }}</AgentCode>
    <UserName>{{ config('app.agent.user_name') }}</UserName>
    <Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
  <Booking>
    <ArrivalDate>{{ $check_in }}</ArrivalDate>
    <DepartureDate>{{ $check_out }}</DepartureDate>
    <CountryCode>{{ $country }}</CountryCode>
    <City>{{ $city }}</City>
    <GuestNationality>{{ $nationality }}</GuestNationality>
    <HotelRatings>
      @if ($star_rating)
        <HotelRating>{{ $star_rating }}</HotelRating>
      @else
        <HotelRating>1</HotelRating>
        <HotelRating>2</HotelRating>
        <HotelRating>3</HotelRating>
        <HotelRating>4</HotelRating>
        <HotelRating>5</HotelRating>
      @endif
    </HotelRatings>
    <Rooms>
      @for ($i = 0; $i < $rooms; $i++)
        <Room>
          <Type>Room-{{ $i + 1 }}</Type>
          <NoOfAdults>{{ $adult[$i] }}</NoOfAdults>
          <NoOfChilds>{{ $child[$i] }}</NoOfChilds>
          @if ($child[$i])
            <ChildrenAges>
              @for ($j = 0; $j < $child[$i]; $j++)
                <ChildAge>{{ $child_age[$i][$j] }}</ChildAge>
              @endfor
            </ChildrenAges>
          @endif
        </Room>
      @endfor
    </Rooms>
  </Booking>
</HotelFindRequest>
