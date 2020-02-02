@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
    <form
    action="{{ !empty($type) ? route('admin.type.edit', [$type->id]) : route('admin.type.store') }}"
    method="post"
    enctype="multipart/form-data">
     @include('partials._info')
     @include('partials._errors')
     @include('admin.type._form')
     {{ method_field('put') }}
    </form>
    </div>
</div>
@endsection