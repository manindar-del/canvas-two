
<div class="panel panel-default type_section">
    <div class="panel-heading">{{ isset($type) ? 'Types' : 'Types' }}</div>
        <div class="panel-body">



                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Name*</label>
                    <div class="col-md-5">
                        <input id="name" type="text" class="form-control" name="name" value="{{$type->name ?? old('name') }}" required />
                    </div>
                </div>

                <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                    <label for="value" class="col-md-4 control-label">Value</label>
                    <div class="col-md-5">
                        <input id="value" type="text" class="form-control" name="value" value="{{$type->value ?? old('value') }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-5 col-md-offset-4">
                        <input class="btn btn-success" type="Submit" value="add">
                    </div>
                </div>



        </div>
    </div>
</div>


