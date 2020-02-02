@extends('layouts.agent')

@section('content')

    {{-- <div class="container">
        <div class="row">

            <div class="col-sm-9 col-sm-push-3">

            </div>

            <div class="col-sm-3 col-sm-pull-9">
                @include('agent.search._widget-filter-hotel')
                @include('agent.search._widget-rating')
                @include('agent.search._widget-price')
                @include('agent.search._widget-city')
            </div>

        </div>
    </div> --}}

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
                        <form action="{{ route('tours.search') }}" method="POST">
                            @include('agent.home._form-tour')
                            {{ csrf_field() }}
                        </form>
                    </div>

                    {{-- <div class="hotelSearfchModifier catSection">

                        <h4 class="secHead">modify search</h4>

                        <form class="clearfix">

                            <div class="form-group">
                                <label>Location</label>
                                <input class="formn-control" type="text">
                            </div>

                            <div class="form-group">
                                <label>Check in</label>
                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                <input class="formn-control datepicker" type="text">
                            </div>

                            <div class="form-group">
                                <label>Check out</label>
                                <span><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
                                <input class="formn-control datepicker" type="text">
                            </div>

                            <div class="form-group col-lg-6">
                                <label>Adult</label>
                                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                                <select class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label>Rooms</label>
                                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
                                <select class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="btn" type="submit">search now</button>
                            </div>

                        </form>

                    </div> --}}

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

                    {{-- <div class="catSection hotelSearchByArea searchByRating">

                        <h4 class="secHead">city area</h4>

                        <form class="clearfix">

                            @foreach ($available_cities as $_city)
                                <div class="form-group clearfix" id="atlanta">
                                    <label for="fiveStar" class="radioLookAlike"></label>
                                    <input type="radio" id="fiveStar" class="d-none">

                                    <div class="starRating">
                                        <h6>{{ $_city['name'] }}</h6>
                                    </div>

                                    <p>( <span>{{ $_city['count'] }}</span> )</p>
                                </div>
                            @endforeach

                            <div class="form-group resetFilter clearfix selectedRating">
                                <label for="fiveStar" class="radioLookAlike"></label>
                                <input type="radio" id="" class="d-none" value="">

                                <div class="starRating">
                                    <h6>Reset</h6>
                                </div>

                                <!--<p>( <span></span> )</p>-->

                            </div>

                        </form>

                    </div> --}}

                </div>


                <div class="col-lg-8 col-md-12 hotelLists clearfix">

                    @if (count($tours))
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
                    @else
                        <div class="alert alert-danger">
                            <h5>Sorry, no records found</h5>
                        </div>
                    @endif

                    @if (count($tours))
                        <div class="hotelDescriptions clearfix" id="pagination-1">
                            @if (count($tours))
                                @if (is_array($tours))
                                    @foreach ($tours as $_entry)
                                        @include('agent.tours._entry')
                                    @endforeach
                                @else
                                    @foreach ($tours as $_entry)
                                        @include('agent.tours._entry')
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </section>

@endsection

@push('footer-bottom')
    {{-- <script>
        var marginSlider = document.getElementById('priceSlider'),
            priceMin = document.getElementById('priceMin'),
            priceMinLabel = document.getElementById('priceMinLabel'),
            priceMax = document.getElementById('priceMax'),
            priceMaxLabel = document.getElementById('priceMaxLabel');

        noUiSlider.create(marginSlider, {
            start: [
                {{ Request::get('price_min') ? Request::get('price_min') : $min }},
                {{ Request::get('price_max') ? Request::get('price_max') : $max }}
            ],
            margin: 30,
            range: {
                'min': parseInt(priceMin.value),
                'max': parseInt(priceMax.value)
            },
            step: 1,
        });

        marginSlider.noUiSlider.on('update', function ( values, handle ) {
            if ( handle ) {
                priceMax.value = values[handle];
                priceMaxLabel.innerText = values[handle];
            } else {
                priceMin.value = values[handle];
                priceMinLabel.innerText = values[handle];
            }
        });
    </script> --}}

    <script>
        jQuery('.city').val(jQuery('.city').find('option[selected]').attr('value'));
    </script>

@endpush