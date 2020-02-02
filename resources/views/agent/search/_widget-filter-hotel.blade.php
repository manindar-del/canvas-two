<div class="_widget hotel">
    <h3 class="_widget__title">Hotel</h3>
    <form action="" class="form-horizontal">
        <div class="form-group{{ $errors->has('hotel_name') ? ' has-error' : '' }}">
            <label for="hotel_name" class="col-md-4 control-label">Hotel name</label>
            <div class="col-md-8">
                <input type="text" name="hotel_name" value="{{ Request::get('hotel_name') }}" class="form-control" />

                @if ($errors->has('hotel_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('hotel_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="_action">
            <button class="btn btn-primary" type="submit">Apply</button>
            <button class="btn btn-info" type="reset">Clear</button>
        </div>
    </form>
</div>