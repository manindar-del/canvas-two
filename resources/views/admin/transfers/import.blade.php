@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">

        <div class="panel-heading">Import Members</div>

        <div class="panel-body">

            @include('partials._info')
            @include('partials._errors')

            <form method="POST" action="{{ route('members.import.store') }}" enctype="multipart/form-data" class="form-horizontal">

                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">Import Members</label>

                    <div class="col-md-6">
                        <input id="excel_file" type="file" name="excel_file" required />

                        @if ($errors->has('excel_file'))
                            <span class="help-block">
                                <strong>{{ $errors->first('excel_file') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-md btn-primary">Save</button>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection