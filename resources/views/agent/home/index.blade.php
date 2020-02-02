@extends('layouts.agent')

@section('content')

{{-- <section class="banner-area relative innerBanner hotelDescBanner" id="home" style="background-image: url({{ asset('assets/img/afterloginBanner.jpg') }});">
    <div class="overlay"></div>
    <div class="container">
        <div class="row align-items-center justify-content-between d-flex">
            <div class="col-md-12 col-lg-6 offset-lg-6 afterLoginBanner">
                <h1>Great Hotel Deals </h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                <a href="#" class="btn">Find out more</a>
            </div>
        </div>
    </div>
</section> --}}

<section class="booking">
    <div class="container">

        @include('partials._info')
        @include('partials._errors')

        <div class="row">
            <div class="col-lg-7">
                <div class="_tab">
                    <ul class="_tabs">
                       {{-- <li class="active"><a href="#hotels" data-value='hotels' data-toggle="tab">Accomodation</a></li> --}}
                        <li class="active"><a href="#activity" data-value='activity' data-toggle="tab">Activities</a></li>
                        <li><a href="#transfer" data-value='transfer'  data-toggle="tab">Transfers</a></li>
                        {{-- <li><a href="package" data-toggle="tab">Package</a></li> --}}
                    </ul>

                    <div class="tab-content _tabContent">
                        {{-- <div class="tab-pane active" id="hotels">
                            @include('agent.home._form-hotels')
                        </div> --}}
                        <div class="tab-pane active" id="activity">
                            @include('agent.home._form-tour')
                        </div>
                        <div class="tab-pane" id="transfer">
                            @include('agent.home._form-transfer')
                        </div>
                        {{-- <div class="tab-pane" id="package">
                            @include('agent.home._form-package')
                        </div> --}}
                    </div>
                </div>

            </div>

            <div class="col-lg-5">
                @include('agent.home._info')
            </div>
        </div>

        @if ($logs->count())
            <div class="col-xs-12">
                <h2 class="_recentHeadline">Recent Searches</h2>
            </div>
            @foreach ($logs as $_log)
                @php
                  $_img = asset('images/hotel.png');
                    if ('hotel' == $_log->search_type && !empty($_log->data) && !empty($_log->data->MainImage)) {
                        $_img = $_log->data->MainImage;
                    }
                    if ('tour' == $_log->search_type) {
                        $_tour = App\Tour::find($_log->search_item_id);
                        if (!empty($_tour) && !empty($_tour->featured_image)) {
                            $_img = asset('storage/' . $_tour->featured_image);
                        }
                    }
                    if ('transfer' == $_log->search_type) {
                        $_transfer = App\Transfer::find($_log->search_item_id);
                        if (!empty($_transfer) && !empty($_transfer->featured_image)) {
                            $_img = asset('storage/' . $_transfer->featured_image);
                        } else {
                            $_img = asset('images/transfer.png');
                        }
                    }
                @endphp
                <div class="col-sm-6 col-lg-3">
                    <article class="_recent">
                        <div class="_recent__feat">
                            <img src="{{ $_img }}" alt="" class="_recent__feat__img" />
                        </div>
                        <h3 class="_recent__title">{{ $_log->description }}</h3>
                    </article>
                </div>
            @endforeach
        @endif

    </div>

    <!--<div class="history_type">
        <div class="_page_">
           <div class="container-fluid">
                <div class="heading_">
                        <h3>Search History</h3>
                    </div>
                @include('partials._info')
                 @include('partials._errors')

                  <table class="table table-stripped" id="history"style="display: none;">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Search Type</th>
                              <th>Description</th>
                          </tr>
                      </thead>
                   <tbody>
                          @foreach ($logs as $_log)
                              <tr>
                                  <td>{{ $_log->id }}</td>
                                  <td>{{ $_log->search_type }}</td>
                                  <td>{{ $_log->description }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
      </div>-->

</section>

<div class="_loader" id="loader" style="background-image: url({{ asset('images/loader.jpg') }})">
    <img class="_loader__img" src="{{ asset('images/loader.gif') }}" alt="" />
</div>

@endsection

@push('footer-bottom')
    <script src="{{ asset('assets/js/moment.js') }}"></script>

    <script>
        jQuery('#searchForm').on('submit', function(e) {
            jQuery('#loader').show();
        })
        jQuery('.select-2').select2();

        $('ul._tabs li a').click(function (e) {
            $('ul._tabs li.active').removeClass('active')
            $(this).parent('li').addClass('active')
            $('.tab-content div.active').removeClass('active');
            var link = $(this).data('value');
            $("#"+link).addClass("active");

        })

        // $('.check_in').on('change')
        jQuery(document).on('change', '.check_in', function(e) {
            var $this = jQuery(this);
            var date = moment(jQuery(this).val(), "MM/DD/YYYY");
            $this.val(date.format('DD/MM/YYYY'));
            // jQuery('.check_out').val(date.add(2, 'days').format('DD/MM/YYYY'));
            var minDate = date.add(2, 'days');
            var today = moment();
            console.log(minDate.diff(today, 'days'));
            jQuery('.check_out.datepicker2').datepicker('destroy');
            jQuery('.check_out.datepicker2').val('');
            jQuery('.check_out.datepicker2').datepicker({
                format: 'DD/MM/YYYY',
                minDate: '+' + minDate.diff(today, 'days') + 'd'
            });
        });

        jQuery(document).on('change', '.check_out', function(e) {
            var $this = jQuery(this);
            var date = moment(jQuery(this).val(), "MM/DD/YYYY");
            jQuery('.check_out').val(date.format('DD/MM/YYYY'));
        });

    </script>
@endpush

@push('footer-bottom')
    <script>
        jQuery('.datepicker2').datepicker({
            format: 'DD/MM/YYYY',
            minDate: '+2d'
        });
        @if ('activity' == Request::get('tab'))
            jQuery('[href="#activity"]').click();
        @endif
        @if ('transfer' == Request::get('tab'))
            jQuery('[href="#transfer"]').click();
        @endif
    </script>

    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/nouislider.min.js') }}"></script>
    <script>
        jQuery('#history').dataTable({
            sort: [],
            responsive: true
        }).show();
    </script>


@endpush