@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        <div class="_entry">

            @php
                // dd($hotel);
              // dd($rooms)
            @endphp

            <div class="_entry__section">
                <div class="row">
                    <div class="col-sm-3">
                        @if (!empty($hotel->MainImage) && is_string($hotel->MainImage))
                            <img src="{{ $hotel->MainImage }}" alt="" class="_entry__img" />
                        @else
                            <img src="{{ asset('images/noimage.gif') }}" alt="" class="_entry__img" />
                        @endif
                    </div>
                    <div class="col-sm-9">
                        <h1 class="_entry__name">{{ $hotel->HotelName }}</h1>
                        <div class="_entry__address">{{ $hotel->HotelAddress }}</div>
                        <div class="_entry__country">{{ $hotel->Country }}</div>
                        <div class="_entry__location">{{ $hotel->Description }}</div>
                    </div>
                </div>
            </div>

            <div class="_entry__section">
                <div class="_entry__sub">
                    Result For :
                    <small>
                        {{ $form['check_in'] }} - {{ $form['check_out'] }} {{ $form['rooms'] }} Room(s)
                    </small>
                </div>
                <ul class="_entry__nav">
                    <li><a href="#rate">Rate</a></li>
                    <li><a href="#information">Information</a></li>
                    <li><a href="#pictures">Pictures</a></li>
                    <li><a href="#googleMap">Map</a></li>
                    <li><a href="#facilities">Facilities</a></li>
                </ul>
            </div>

            <div class="_entry__section" id="rate">
                <h2 class="_entry__section__title">Rate</h2>
                <div class="_form _bookingForm">
                    <div class="_bookingForm__header">
                        <div class="row">
                            <div class="col-xs-1">Select</div>
                            <div class="col-xs-3">Room Type</div>
                            <div class="col-xs-2">Board</div>
                            <div class="col-xs-2">Avg</div>
                            <div class="col-xs-2">Total</div>
                            <div class="col-xs-2">Status</div>
                        </div>
                    </div>
                    @foreach ($rooms as $_room)

                        @php
                            // dd($_room)
                        @endphp

                        @if (!empty($_room->cancellation_policy) && !empty($_room->cancellation_policy->CancellationInformations))
                            @php
                                $_canellation = $_room->cancellation_policy->CancellationInformations;
                                $CancellationInformation = $_canellation->CancellationInformation;
                                if (!is_array($CancellationInformation)) {
                                    $CancellationInformation = [$CancellationInformation];
                                }
                                $rate = explode('|', $_room->TotalRate)[0];
                                $rate = \App\Helpers\PriceHelper::getHikedHotelPrice($rate, Auth::user());
                                $rate = ceil($rate);
                                $total_rate = $_room->TotalRooms * $rate;
                                $charge = 0;
                                // $currency = $_canellation->CancellationInformation->Currency;
                                // $charge = ceil($_canellation->CancellationInformation->ChargeAmount);
                            @endphp
                            <div class="_bookingForm__item">
                                <div class="row">
                                    <div class="col-xs-1">
                                        <input type="radio" value="" name="room" class="_bookingForm__checkbox" />
                                    </div>
                                    <div class="col-xs-3">{{ explode('|', $_room->Type)[0] }}</div>
                                    <div class="col-xs-2">{{ explode('|', $_room->BoardBasis)[0] }}</div>
                                    <div class="col-xs-2">{{ $rate }}</div>
                                    <div class="col-xs-2">{{ $total_rate }}</div>
                                    <div class="col-xs-2"><strong class="text-success">Available</strong></div>
                                    <div class="col-xs-12 _bookingForm__item__info">
                                        <p><strong>Remark</strong></p>
                                        <p>{{ $_canellation->Info }}</p>
                                        <p class="text-warning">Cancellation Policy</p>
                                        @foreach ($CancellationInformation as $_info)
                                            @php
                                                if ($charge < $_info->ChargeAmount) {
                                                    $charge = $_info->ChargeAmount;
                                                }
                                            @endphp
                                            @if (ceil($_info->ChargeAmount))
                                                <p>
                                                    A cancellation penalty of
                                                    <strong class="text-danger">
                                                        {{ $_info->Currency }}
                                                        {{ ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_info->ChargeAmount, Auth::user())) }}
                                                    </strong>
                                                    will be applied if cancel from
                                                    <strong>{{ date('d M Y', strtotime($_info->StartDate)) }}</strong>
                                                    onward
                                                </p>
                                            @endif
                                        @endforeach
                                        <hr />
                                        <p class="text-right">
                                            <!--@if (Auth::user()->available_wallet_balance > $charge)
                                                <a
                                                    href="{{ route('hotels.prebook', [$search_id, $hotel_id, $_room->BookingKey]) }}"
                                                    class="btn btn-primary"
                                                >Add To Cart</a>
                                            @else
                                                <h4 class="text-danger">Insufficient balance in wallet.</h4>
                                            @endif-->

                                                 <a
                                                    href="{{ route('hotels.prebook', [$search_id, $hotel_id, $_room->BookingKey]) }}"
                                                    class="btn btn-primary"
                                                >Add To Cart</a>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="_entry__section" id="information">
                <h2 class="_entry__section__title">Information</h2>
                <p>{{ $hotel->Description }}</p>
            </div>

            <div class="_entry__section" id="pictures">
                <h2 class="_entry__section__title">Pictures</h2>
                @if (!empty($hotel->Images))
                    <div class="row">
                        @foreach ($hotel->Images as $_image)
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <img src="{{ $_image }}" alt="" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="_entry__section" id="googleMap">
                <h2 class="_entry__section__title">Map</h2>
                @if (!empty($hotel->Latitude) && !empty($hotel->Longitude))
                    <div style="height:440px;">
                        <div id="gmap_canvas" style="height:440px;"></div>
                    </div>
                    <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ config('app.google_maps_api_key') }}'></script>
                    <script type='text/javascript'>
                        function init_map(){
                            var myOptions = {
                                zoom:17,
                                center:new google.maps.LatLng(51.507351,-0.127758),
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                streetViewControl:false
                            };
                            map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                            marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(51.507351,-0.127758)});
                            infowindow = new google.maps.InfoWindow({content:'<strong>{{ $hotel->HotelName }}</strong><br>{{ $hotel->City . ', ' . $hotel->Country }}<br>'});google.maps.event.addListener(marker, 'click', function(){
                                infowindow.open(map,marker);
                            });
                            infowindow.open(map,marker);
                        }
                        google.maps.event.addDomListener(window, 'load', init_map);
                    </script>
                @endif
            </div>

            <div class="_entry__section" id="facilities">
                <h2 class="_entry__section__title">Facilities</h2>
                @if ($hotel->HotelAmenities)
                    <ul class="row _entry__amenities">
                        @foreach (explode(',', $hotel->HotelAmenities) as $_amenity)
                            <li class="col-sm-6 col-md-4">{{ $_amenity }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection