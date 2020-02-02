@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">Upload Hotel Contract Document</div>
                <div class="panel-body">
                    <form action="{{route('admin.upload.hotel-contract.store') }}" method="post" enctype="multipart/form-data">

                        @if (Session::has('message'))
                            <div class="col-md-12">
                                <div class="alert alert-info">{{ Session::get('message') }}</div>
                            </div>
                        @endif

                        {{-- {{ csrf_field() }}
                        <label for="file_upload" class="col-md-4 control-label">File Upload</label>
                        <div class="col-md-6">
                            <input id="file_upload" type="file" class="form-control" name="transfer" value="file">
                            <div class="button_upload">
                                <input class="btn btn-success" type="Submit" value="Upload File">
                            </div>
                        </div> --}}

                        <div class="form-group clearfix{{ $errors->has('contract') ? ' has-error' : '' }}">
                            <label for="contract" class="col-md-4 control-label">File Upload*</label>
                            <div class="col-md-5">
                                <input id="file_upload" type="file" class="form-control" name="contract" value="file">
                                @if ($errors->has('contract'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contract') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-md-offset-4">
                                <button type="submit" class="btn btn-md btn-primary">Upload</button>
                                {{ csrf_field() }}
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
