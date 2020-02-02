@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <form
            method="POST"
            action="{{ route('agents.wallets.store', [$agent->id]) }}"
            enctype="multipart/form-data"
            class="form-horizontal"
        >
            @include('partials._info')
            @include('partials._errors')
            @include('admin.wallets._form')
        </form>
    </div>
</div>
@endsection