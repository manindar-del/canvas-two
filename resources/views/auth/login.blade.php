@extends('layouts.agent')

@section('content')
<div class="_page _loginPage" style="background-image: url({{ asset('resources/img/CV-Login.jpg') }}); background-size: cover; -webkit-background-size: cover;">
	<div class="container">
		<div class="_auth" style="margin: 50px auto;">
			@include('partials._info')
			@include('partials._errors')
			<div class="row">
				<div class="col-md-6">
					<form class="form-horizontal _auth__form" method="POST" action="{{ route('login') }}">
						<div class="_auth__form__group">
							<input type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" placeholder="User Name" required autofocus />
							<input type="password" class="form-control" name="password" placeholder="Password" required />
							<input type="password" class="form-control hidden" name="password2" placeholder="" />
						</div>
						<div class="_auth__form__footer">
							<a class="_auth__link" href="{{ route('password.request') }}">
								Forgot Your Password?
							</a>
							{{-- <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me --}}
							<div>
								<span class="_switchLabel">Remember Me</span>
								<div class="_switch">
									<input id="remember" type="checkbox" class="_switch__input" name="remember" checked />
									<label for="remember" class="_switch__label">
										{{-- <span class="_switch__on">on</span> --}}
										<span class="_switch__circle"></span>
										{{-- <span class="_switch__off">off</span> --}}
									</label>
								</div>
							</div>
						</div>
						<div class="form-footer">
							<button type="submit" class="btn btn-block btn-primary">
								Login
							</button>
							{{ csrf_field() }}
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<div class="_auth__info">
						<h3 class="_auth__headline">Become a customer</h3>
						<p>CanvasVacation is a state of the art user friendly online system developed exclusively for the Travel Trade.</p>
						<p>Our business plan is to provide travel partners with a tailor made and technology supported approach towards the destination Thailand and other destination in future.</p>
						<p>Obtain access to net wholesale rates and real time inventory for various transfers and sightseeing . Manage your bookings online under My Booking Section.</p>
						<div class="form-footer">
							<a class="btn btn-block btn-primary" href="{{ route('register') }}">Haven't Registered Yet?</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
