@extends('layouts.agent')

@section('content')
	<section class="booking_wallet">
		<div class="container">

			<div class="booking_wallet__card">

				@include('partials._errors')
				@include('partials._info')

				@if (Session::has('message'))
					<div class="alert alert-info">{{ Session::get('message') }}</div>
				@endif

				<div class="row">
					<div class="col-sm-6">
						<img class="booking_wallet__icon" src="{{ asset('images/mobile-banking-e-money-illustration_1893-2211.jpg') }}" alt="">
					</div>
					<div class="col-sm-6">
						<form action="{{route('wallet.store') }}" method="post" class="booking_wallet__form">
							<div class="form-group form_text">
								<span class="booking_wallet__info">Available Balance</span>
								<h1 class="booking_wallet__headline">&#8377;{{ Auth::user()->available_wallet_balance }}</h1>
								<input class="form-control booking_wallet__input" rows="7" type="text" name="amount" cols="50" id="amount" placeholder="&#8377;999">
							</div>
							<div class="button">
								<input class="btn btn-success btn-block booking_wallet__btn" type="submit" value="Add Money To Wallet">
								{{ csrf_field() }}
							</div>
						</form>
					</div>
				</div>

			</div>

		</div>
	</section>
@endsection
