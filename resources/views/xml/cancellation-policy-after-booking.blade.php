<GetCancellationPolicyAfterBookingRequest>
  <Authentication>
		<AgentCode>{{ config('app.agent.code') }}</AgentCode>
		<UserName>{{ config('app.agent.user_name') }}</UserName>
		<Password>{{ config('app.agent.password') }}</Password>
  </Authentication>
	<SearchSessionId>{{$json_data->SearchSessionId}}</SearchSessionId>
	<BookingId>{{ $booking_id }}</BookingId>
	<BookingCode>{{ $booking_code }}</BookingCode>
</GetCancellationPolicyAfterBookingRequest>
