@if (!empty($_entry->hotel))
    <div class="row">

        <div class="col-lg-9 col-md-12 hotelNameDeatils">
            <div class="row">
                <div class="col-lg-6 col-md-12 relative hotelImg clearfix">

                    <div class="gradientOverlay"></div>

                    <img class="back" src="{{ $_entry->ThumbImages }}" alt="{{ $_entry->Name }}">

                    <div class="hotelRating">
                        @if (!empty($_entry->Rating))
                            @for ($r = 0; $r < $_entry->Rating; $r++)
                                <i class="fa fa-star" aria-hidden="true"></i>
                            @endfor
                        @endif
                        {{-- <a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a> --}}
                    </div>

                    <a href="{{ route('hotels.show', [$search_id, $_entry->Id]) }}" class="viewOnMap"><i class="fa fa-map-marker" aria-hidden="true"></i> view on map</a>

                </div>

                <div class="col-lg-6 col-md-12 hotelDetails">

                    <h4 class="hotelName">{{ $_entry->Name }}</h4>

                    @if (!empty($_entry->hotel->city))
                        <p class="hotelAddress"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>{{ $_entry->hotel->city }}</p>
                    @endif

                    <p class="hotelAddress2"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>{{ $_entry->hotel->hotel_address }}</p>

                    <p>{{ substr($_entry->hotel->desc, 0, 100) }}</p>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-12 confirmHotelBooking text-lg-center text-md-left">

            <h4>{{ $json->Currency }} <span>{{ ceil(\App\Helpers\PriceHelper::getHikedHotelPrice($_entry->Price, Auth::user())) }}</span></h4>
            <p>From/per night</p>
            <a href="{{ route('hotels.show', [$search_id, $_entry->Id]) }}" class="btn text-capitalize">book this now</a>

        </div>

    </div>
@endif