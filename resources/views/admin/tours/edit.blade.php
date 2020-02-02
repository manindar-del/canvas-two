@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <form
            method="POST"
            action="{{ route('tours.update', $tour->id) }}"
            enctype="multipart/form-data"
            class="form-horizontal"
        >
            @include('partials._info')
            @include('partials._errors')
            @include('admin.tours._form')
            {{ method_field('put') }}
        </form>
    </div>
</div>
@endsection