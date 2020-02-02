<HotelDetailsRequest>
  <Authentication>
    <AgentCode>{{ config('app.agent.code') }}</AgentCode>
    <UserName>{{ config('app.agent.user_name') }}</UserName>
    <Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
  <Hotels>
    <HotelId>{{ $id }}</HotelId>
  </Hotels>
</HotelDetailsRequest>