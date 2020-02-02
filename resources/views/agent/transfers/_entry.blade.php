@if (!empty($_entry))
    <div class="row">

        <div class="col-lg-9 col-md-12 hotelNameDeatils">
            <div class="row">
                <div class="col-lg-6 col-md-12 relative hotelImg clearfix">
                    <div class="gradientOverlay"></div>
                    @if (!empty($_entry->featured_image))
                        <img class="back" src="{{asset('storage/' . $_entry->featured_image)}}" alt="{{ $_entry->Name }}" />
                    @else
                        <img class="back" src="{{asset('images/hotel.png')}}" alt="{{ $_entry->Name }}" />
                    @endif
                    <div class="hotelRating">
                        @if (!empty($_entry->Rating))
                            @for ($r = 0; $r < $_entry->Rating; $r++)
                                <i class="fa fa-star" aria-hidden="true"></i>
                            @endfor
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 hotelDetails">
                    <h4 class="hotelName">{{ $_entry->title }}</h4>
                    <p class="hotelAddress2"><strong>Tour Starting At:</strong> {{ $_entry->start_time }} - {{ $_entry->end_time }}</p>
                    <p><strong>Tour Type:</strong> {{ $_entry->type }}</p>
                    <div class="hotelDescription">{{ strlen($_entry->details) > 25 ? substr($_entry->details, 25) . '&hellip;' : $_entry->details }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-12 confirmHotelBooking text-lg-center text-md-left">
            <p>Lowest Rate</p>

            <h4>{{ $allSymbol->sign }}  <span>{{ ceil($_entry->adult_price) }}</span></h4>

            <p> <a href="{{ route('transfers.search.show', [$search_id, $_entry->id]) }}" class="btn text-capitalize">View more</a></p>
            <p class="text-success">Available</p>
        </div>

    </div>

@endif