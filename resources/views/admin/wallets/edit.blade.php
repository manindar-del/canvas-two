@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <form
            method="POST"
            action="{{ route('agents.wallets.update', [$agent->id, $wallet->id]) }}"
            enctype="multipart/form-data"
            class="form-horizontal"
        >
            @include('partials._info')
            @include('partials._errors')
            @include('admin.wallets._form')
            {{ method_field('put') }}
        </form>
    </div>
</div>
@endsection