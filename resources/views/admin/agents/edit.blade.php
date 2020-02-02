@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <form
            method="POST"
            action="{{ route('agents.update', $agent->id) }}"
            enctype="multipart/form-data"
            class="form-horizontal"
        >
            @include('partials._info')
            @include('partials._errors')
            @include('admin.agents._form')
            {{ method_field('put') }}
        </form>
    </div>
</div>
@endsection