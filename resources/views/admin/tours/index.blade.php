@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        <table class="table table-stripped" id="tours" style="display: none;">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>No of Adult</th>
                    <th>No of Child</th>
                    <th>No of Infant</th>
                    <th class="table__actions">Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($tours as $_tours)

                    <tr>
                        <td>{{ $_tours->id }}</td>
                        <td>{{ $_tours->title }}</td>
                        <td>{{ $_tours->type }}</td>
                        <td>{{ $_tours->start_time }}</td>
                        <td>{{ $_tours->end_time }}</td>
                        <td>{{ $_tours->no_of_adult }}</td>
                        <td>{{ $_tours->no_of_child }}</td>
                         <td>{{ $_tours->no_of_infant }}</td>
                        <td class="table__actions">

                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal{{ $_tours->id }}">
                            Cancellation Details
                            </button>

                            <!-- Modal for Cancellation -->
                            <div class="modal fade" id="exampleModal{{ $_tours->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-left" id="exampleModalLabel">{{ $_tours->title }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Days</th>
                                        <th>Adult Amount</th>
                                        <th>Child Amount</th>
                                        <th>Infant Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                       <td>
                                       @if(!empty($_tours->cancellation_date->cancellation_date))
                                           @foreach ($_tours->cancellation_date->cancellation_date as $_gdata)
                                                 <div> {{ $_gdata }}</div>
                                            @endforeach
                                        @endif
                                        </td>
                                        <td>
                                        @if(!empty($_tours->cancellation_date->adult_amount))
                                           @foreach ($_tours->cancellation_date->adult_amount as $_adult_amount)
                                                 <div> {{ $_adult_amount }}</div>
                                            @endforeach
                                        @endif
                                        </td>
                                        <td>
                                         @if(!empty($_tours->cancellation_date->child_amount))
                                            @foreach ($_tours->cancellation_date->child_amount as $_child_amount)
                                                 <div> {{ $_child_amount }}</div>
                                            @endforeach
                                        @endif
                                        </td>
                                        <td>
                                         @if(!empty($_tours->cancellation_date->infant_amount))
                                            @foreach ($_tours->cancellation_date->infant_amount as $_infant_amount)
                                                 <div> {{ $_infant_amount }}</div>
                                            @endforeach
                                         @endif
                                        </td>

                                    </tr>

                                    </tbody>
                                </table>

                                </div>
                                <div class="clearfix">&nbsp;</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                </div>
                                </div>
                            </div>
                            </div>
                          <!-- Modal for Cancellation End-->

                         <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#galleryimageModal{{ $_tours->id }}">
                            Gallery Images
                            </button>

                            <!-- Modal for gallery_image -->
                            <div class="modal fade" id="galleryimageModal{{ $_tours->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content  text-left">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ $_tours->title }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body  text-left">
                                <div class="col-md-12">
                                <h3>Featured Image</h3>
                                <div class="col-md-12">
                                    <img src="{{asset('storage/' . $_tours->featured_image)}}">
                                </div>
                                <h3>Gallery Images</h3>
                                @foreach ($_tours->gallery_image as $_gallery_image)
                                <div class="col-md-3"> <img src="{{  asset('storage/' . $_gallery_image) }}" /> </div>
                                @endforeach

                                </div>
                                <div class="clearfix">&nbsp;</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                </div>
                                </div>
                            </div>
                            </div>
                          <!-- Modal for gallery_image End-->



                            <a href="{{ route('tours.edit', [$_tours->id]) }}" class="btn btn-sm btn-info">
                                Edit
                            </a>

                            <a href="#" onClick="event.preventDefault(); confirm() && form{{ $_tours->id }}.submit()" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                            <form action="{{ route('tours.destroy', [$_tours->id]) }}" method="post" id="form{{ $_tours->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection

@push('footer-bottom')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/nouislider.min.js') }}"></script>
<script>
    jQuery('#tours').dataTable({
        sort: [],
        responsive: true
    }).show();
</script>
@endpush