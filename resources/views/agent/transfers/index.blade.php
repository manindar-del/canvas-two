@extends('layouts.agent')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
@section('content')

    {{-- <section class="banner-area relative innerBanner hotelSearch" id="home" style="background-image: url({{ asset('assets/img/hotels.jpeg') }});">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
            </div>
        </div>
    </section> --}}

    <section class="hotelSearch">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-12 hotelFilterCriteria clearfix">

                    <div class="catSection catSectionForm">
                        <h4 class="secHead">Search</h4>
                        <form action="{{ route('transfers.search') }}" method="POST">
                            @include('agent.home._form-transfer')
                            {{ csrf_field() }}
                        </form>
                    </div>


                    <div class="catSection filterHeader">
                        <h4 class="secHead">filter by</h4>
                    </div>

                    <div class="catSection searchHotelByName hotelSearfchModifier">
                        <h4 class="secHead">Tour name</h4>
                        <form class="clearfix">
                            <div class="form-group">
                                <span><i class="fa fa-search" aria-hidden="true"></i></span>
                                <input class="formn-control searchKey" type="text" placeholder="Search" required>
                            </div>
                            <div class="form-group">
                                <button class="btn" type="">search now</button>
                                <button type="reset" href="#" class="resetSearch btn">Clear</button>
                            </div>
                        </form>
                    </div>

                    <div class="catSection rangeSlider clearfix">
                        <h4 class="secHead">price range</h4>
                        <div id="slider"></div>
                        <form class="clearfix">
                            <span>
                                <label>{{ Auth::user()->currency }}</label>
                                <span class="minPriceSpn"></span>
                                <input id="minPrice" type="hidden" class="sliderValue" data-index="0" value="" />
                            </span>
                            <span>-</span>
                            <span>
                                <label>{{ Auth::user()->currency }}</label>
                                    <span class="maxPriceSpn"></span>
                                <input id="maxPrice" type="hidden" class="sliderValue" data-index="1" value="" />
                            </span>

                            <div class="form-group d-block">
                                <a href="#" class="btn priceSearch" type="">search now</a>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-lg-8 col-md-12 hotelLists clearfix">
                    <div class="clearfix">
                        <ul class="sortControl">
                            <li>
                                <select class="sortParam">
                                    <option value="default">Default Sort</option>
                                    <option value="NameAs">Name Ascendeing</option>
                                    <option value="NameDes">Name Descending</option>
                                    <option value="PriceAs">Price Ascendeing</option>
                                    <option value="PriceDes">Price Descending</option>
                                    <option value="RatingAs">Rating Ascendeing</option>
                                    <option value="RatingDes">Rating Descending</option>
                                </select>
                            </li>

                            <li>
                                <select class="numberOfPage">
                                    <option value="10">Result Per page</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="60">60</option>
                                </select>
                            </li>



                        </ul>

                        <ul class="viewControl float-lg-right">
                            <li> <a href="#" class="list"><i class="fa fa-bars" aria-hidden="true"></i></a> </li>
                            <li> <a href="#" class="grid"><i class="fa fa-th-large" aria-hidden="true"></i></a> </li>
                            <li> <a href="#"><i class="fa fa-star-o" aria-hidden="true"></i></a> </li>
                        </ul>

                        <div class="searchWithAlphabet">
                            <ul></ul>
                        </div>
                    </div>

                    <div class="hotelDescriptions clearfix" id="pagination-1">
                        @if (count($transfers))
                            @if (is_array($transfers))
                                @foreach ($transfers as $_entry)
                                    @include('agent.transfers._entry')
                                @endforeach
                            @else
                                @foreach ($transfers as $_entry)
                                    @include('agent.transfers._entry')
                                @endforeach
                            @endif
                        @else
                            <div class="alert alert-danger">
                                <h4>Sorry, no records found</h4>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </section>

@endsection


@push('footer-bottom')
    <script>
        jQuery('.city').val(jQuery('.city').find('option[selected]').attr('value'));
        jQuery('.datepicker2').datepicker({
            format: 'DD/MM/YYYY',
            minDate: '+2d'
        });
    </script>
    {{-- <script>
            $(function() {
                $("#curr").on("change",function() {
                    var dropselvalue = -1;
                    if ($(this).val()) {
                        dropselvalue = $(this).val();
                    }
                    if (window.sessionStorage) {
                        sessionStorage.setItem("current_currency_code", dropselvalue);
                    }
                }).change();
              });


   </script>
 --}}


@endpush