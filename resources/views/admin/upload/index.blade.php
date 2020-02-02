@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container">
        <div class="upload_file">
            <form action="{{route('upload.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <label for="file_upload" class="col-md-4 control-label">File Upload</label>
                <div class="col-md-6">
                    <input id="file_upload" type="file" class="form-control" name="transfer" value="file">
                    <div class="button_upload">
                        <input class="btn btn-success" type="SUBMIT" value="Upload File">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if (Session::has('message'))
 <div class="col-md-12">
 <div class="alert alert-info">{{ Session::get('message') }}</div>
 </div>
@endif