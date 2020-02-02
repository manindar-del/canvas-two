@extends('layouts.agent')

@section('content')
<div class="_page">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        <div class="_entry">

            <div class="_entry__section">
                <div class="row">
                    <div class="col-sm-3">
                        @if (!empty($tours->featured_image) && is_string($tours->featured_image))
                            <img src="{{asset('storage/' . $tours->featured_image)}}" alt="" class="_entry__img" />
                        @else
                            <img src="{{ asset('images/noimage.gif') }}" alt="" class="_entry__img" />
                        @endif
                    </div>
                    <div class="col-sm-9">
                        <h1 class="_entry__name">{{ $tours->title }}</h1>
                        <div class="_entry__address text-success">Tour Starting At :  {{ $tours->start_time }} - {{ $tours->end_time }}</div>
                        <div class="_entry__country">Tour Type : {{ $tours->type }}</div>
                        <div class="_entry__location">{{ $tours->details }}</div>
                        <br>
                        <p>
                            For detailed description please refer to
                            <a target="_blank" class="btn btn-sm btn-primary" href="{{ $contract_doc}}">View Doc</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="_entry__section">
                <ul class="_entry__nav">
                    <li><a href="#rate">Rate</a></li>
                    <li><a href="#information">Information</a></li>
                    <li><a href="#pictures">Pictures</a></li>
                </ul>
            </div>

            <div class="_entry__section" id="rate">

                <h2 class="_entry__section__title">Rate</h2>

                <div class="_form _bookingForm">
                    <div class="_bookingForm__header">
                        <div class="row">
                            <div class="col-xs-1">Select</div>
                            <div class="col-xs-3">Type</div>
                            <div class="col-xs-4">Unit Price</div>
                            <div class="col-xs-2">Total</div>
                            <div class="col-xs-2">Status</div>
                        </div>
                    </div>

                    <div class="_cart__item__title">
                        <div class="_cart__item">
                            <div class="row">

                                <div class="col-xs-1">
                                    <input type="radio" value="{{$tour_id}}" name="tour" class="_bookingForm__checkbox" checked/>
                                </div>

                                <div class="col-xs-3">
                                    <strong>{{ $tours->type }}</strong>
                                </div>

                                <div class="col-xs-4">
                                    @if(!empty($form['form']['adult']))
                                        <div>
                                            Adult : {{$tours->adult_price}} X {{$form['form']['adult']}} Adult(s) = {{ $form['form']['adult'] * $tours->adult_price }}
                                        </div>
                                    @endif
                                    @if(!empty($form['form']['child']))
                                        <div>
                                            Child : {{$tours->child_price}} X {{$form['form']['child']}} Child(ren) = {{ $form['form']['child'] * $tours->child_price }}
                                        </div>
                                    @endif
                                    @if(!empty($form['form']['infant']) && $tours->infant_price)
                                        <div>
                                            Infant : {{$tours->infant_price}} X {{$form['form']['infant']}} Infant(s) = {{ $form['form']['infant'] * $tours->infant_price }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-xs-2">

                                    {{ $allSymbol->sign }} {{ ($form['form']['adult'] * $tours->adult_price) + ($form['form']['child'] * $tours->child_price )+ ($form['form']['infant'] * $tours->infant_price) }}
                                </div>

                                <div class="col-xs-2">
                                    <strong class="text-success">Available</strong>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <p>
                            <strong>Cancellation Policy</strong>
                        </p>
                        <p>
                            No charge if cancelled before
                            @php
                                $cancellation_last_day = strtotime('-7day', strtotime($form['form']['tour_date']));
                            @endphp
                            <strong>{{ date('Y-m-d', $cancellation_last_day) }}</strong>
                            <br>
                            For Cancellation please email us <a href="online@canvasvacations.net">online@canvasvacations.net</a>
                            <br>
                            No cancellation will be accepted without email.
                        </p>
                    </div>

                    <p class="text-right">
                        <a
                            href="{{ route('tours.search.prebook', [$search_id, $tour_id]) }}"
                            class="btn btn-primary"
                        >Add To Cart</a>
                    </p>

                </div>
            </div>

            <div class="_entry__section" id="information">
                <h2 class="_entry__section__title">Information</h2>
                <p>{{ $tours->details }}</p>
            </div>

            <div class="_entry__section" id="pictures">
                <h2 class="_entry__section__title">Pictures</h2>
                @if (!empty($tours->gallery_image))
                    <div class="row">
                        @foreach ($tours->gallery_image as $_image)
                            <div class="col-sm-6 col-md-3 col-lg-2">
                                <img src="{{  asset('storage/' . $_image) }}" alt="" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
