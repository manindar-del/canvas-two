@extends('layouts.agent')

@section('content')
	<section class="payment_wallet">
		<div class="container">
			<table class="table table-stripped">
				<thead>
					<tr>
						<th>Transaction ID</th>
						<th>Amount</th>
						{{-- <th>Type</th> --}}
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($payments as $_payment)
						<tr>
							<td>{{ $_payment->payment->txn_id ?? '-' }}</td>
							<td>&#8377;{{ $_payment->amount }}</td>
							{{-- <td>{{ ucwords($_payment->type) }}</td> --}}
							<td>{{ $_payment->created_at->format('Y-m-d H:i:s a') }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
@endsection
