@extends('layouts.app')

@push('header-top')
    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.10.16/kt-2.3.2/datatables.min.css" />
@endpush

@push('header-bottom')
    <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}" />
@endpush

@section('header')
    {{-- <header class="_header">
        <div class="container">
            <div class="_branding">
                <img src="{{ asset('images/banner.jpg') }}" alt="" class="_logo" />
            </div>
            <nav class="_nav">
                <ul class="_menu">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('agents.index') }}">Agents</a></li>
                </ul>
            </nav>
        </div>
    </header> --}}
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ route('admin.booking.index') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                 {{-- Right Side Of Navbar  --}}
                <ul class="nav navbar-nav navbar-right">

                    {{-- Authentication Links --}}

                    @guest
                        <li><a href="{{ route('login') }}">Login</a></li>
                        {{--  <li><a href="{{ route('register') }}">Register</a></li>  --}}
                    @else

                        <li><a href="{{ route('admin.booking.index') }}">All Booking</a></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Agents<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('agents.index') }}">Agents</a></li>
                                <li><a href="{{ route('agents.create') }}">Add New</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Tours<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('tours.index') }}">Tours</a></li>
                                <li><a href="{{ route('tours.create') }}">Add New</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Transfers<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('transfers.index') }}">Transfers</a></li>
                                <li><a href="{{ route('transfers.create') }}">Add New</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Types<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.type.index') }}">Type</a></li>
                                    <li><a href="{{ route('admin.type.create')}}">Add New Type</a></li>
                                </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->user_name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {{-- <li><a href="{{ route('agents.index') }}">View Agents</a></li>
                                <li><a href="{{ route('agents.create') }}">Add New Agent</a></li>
                                <li><a href="{{ route('tours.index') }}">View Tours</a></li>
                                <li><a href="{{ route('tours.create') }}">Add New Tour</a></li>
                                <li><a href="{{ route('transfers.index') }}">View Transfer</a></li>
                                <li><a href="{{ route('transfers.create') }}">Add New Transfer</a></li> --}}
                                <li><a href="{{ route('admin.upload.transfer.index') }}">Upload Transfer</a></li>
                                <li><a href="{{ route('admin.upload.hotel-contract.index') }}">Upload Hotel Contract</a></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    >Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
@endsection

@section('footer')
    <footer class="_footer adminFooter">
        <div class="container">
            <p class="_copyright">Copyright {{ date('Y') }}</p>
        </div>
    </footer>
@endsection