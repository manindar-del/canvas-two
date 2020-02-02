@if ($msg = session('msg'))
	<div class="alert alert-{{ session('ok') ? 'success' : 'danger' }}">
		<strong>{{ $msg }}</strong>
	</div>
@endif