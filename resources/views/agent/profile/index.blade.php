@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        @include('agent.profile._form')
        

    </div>
</div>
@endsection

