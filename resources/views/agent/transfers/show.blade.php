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
                        @if (!empty($transfers->featured_image) && is_string($transfers->featured_image))
                            <img src="{{asset('storage/' . $transfers->featured_image)}}" alt="" class="_entry__img" />
                        @else
                            <img src="{{ asset('images/transfer.png') }}" alt="" class="_entry__img" />
                        @endif
                    </div>
                    <div class="col-sm-9">
                        <h1 class="_entry__name">{{ $transfers->title }}</h1>
                        <div class="_entry__country">Tour Type : {{ $transfers->type }}</div>
                        <div class="_entry__location">{{ $transfers->details }}</div>
                        <br>
                        <p>
                            For detailed description please refer to
                            <a target="_blank" class="btn btn-sm btn-primary" href="{{ $tranfer_doc}}">View Doc</a>
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
                        <div class="row">

                            <div class="col-xs-1">
                                <input type="radio" value="{{ $transfer_id }}" name="transfer" class="_bookingForm__checkbox" checked/>
                            </div>

                            <div class="col-xs-3">{{ $transfers->type }}</div>

                            <div class="col-xs-4">
                                <p>
                                    @if(!empty($form['form']['adult']))
                                        Adult : {{$transfers->adult_price}} X {{$form['form']['adult']}} Adult(s) = {{ $form['form']['adult'] * $transfers->adult_price }}
                                    @endif
                                </p>
                                <p>
                                    @if(!empty($form['form']['child']))
                                        Child : {{$transfers->child_price}} X {{$form['form']['child']}} Child(ren) = {{ $form['form']['child'] * $transfers->child_price }}
                                    @endif
                                </p>
                            </div>

                            <div class="col-xs-2">
                                {{ $allSymbol->sign }} {{ ($form['form']['adult'] * $transfers->adult_price) + ($form['form']['child'] * $transfers->child_price ) }}

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
                            $cancellation_last_day = strtotime('-7day', strtotime($form['form']['transfer_date']));
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
                        href="{{ route('transfers.search.prebook', [$search_id, $transfer_id]) }}"
                        class="btn btn-primary"
                    >Add To Cart</a>
                </p>
            </div>

            <div class="_entry__section" id="information">
                <h2 class="_entry__section__title">Information</h2>
                <p>{{ $transfers->details }}</p>
            </div>

            <div class="_entry__section" id="pictures">
                <h2 class="_entry__section__title">Pictures</h2>
                @if (!empty($transfers->gallery_image))
                    <div class="row">
                        @foreach ($transfers->gallery_image as $_image)
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
