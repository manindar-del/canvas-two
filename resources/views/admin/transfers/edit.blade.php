@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <form
            method="POST"
            action="{{ route('transfers.update', [$transfer->id]) }}"
            enctype="multipart/form-data"
            class="form-horizontal"
        >
            @include('partials._info')
            @include('partials._errors')
            @include('admin.transfers._form')
            {{ method_field('put') }}
        </form>
    </div>
</div>
@endsection