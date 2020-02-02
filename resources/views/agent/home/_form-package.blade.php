<form class="form-horizontal" method="POST" action="{{ route('hotels.search') }}">

    <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
        <label for="nationality" class="col-md-3 control-label">Nationality</label>

        <div class="col-md-6">
            <select name="nationality" id="nationality" name="nationality" class="form-control">
                @foreach ($nationalities as $_nationality)
                    <option value="{{ $_nationality->id }}" {{ old('nationality') == $_nationality->id ? 'selected' : '' }}>{{ $_nationality->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('nationality'))
                <span class="help-block">
                    <strong>{{ $errors->first('nationality') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('destination') ? ' has-error' : '' }}">
        <label for="destination" class="col-md-3 control-label">Destination</label>

        <div class="col-md-6">
            <input id="destination" type="email" class="form-control" name="destination" value="{{ old('destination') }}" />

            @if ($errors->has('destination'))
                <span class="help-block">
                    <strong>{{ $errors->first('destination') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('check_out') ? ' has-error' : '' }}">
        <label for="check_out" class="col-md-3 control-label">Travel Date</label>

        <div class="col-md-6">
            <input id="check_out" type="email" class="form-control" name="check_out" value="{{ old('check_out') }}" />

            @if ($errors->has('check_out'))
                <span class="help-block">
                    <strong>{{ $errors->first('check_out') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('travel_date') ? ' has-error' : '' }}">
        <label for="travel_date" class="col-md-3 control-label">Package Code</label>

        <div class="col-md-6">
            <input id="travel_date" type="email" class="form-control" name="travel_date" value="{{ old('travel_date') }}" />

            @if ($errors->has('travel_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('travel_date') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('package_code') ? ' has-error' : '' }}">
        <label for="package_code" class="col-md-3 control-label">Package Name</label>

        <div class="col-md-6">
            <input id="package_code" type="email" class="form-control" name="package_code" value="{{ old('package_code') }}" />

            @if ($errors->has('package_code'))
                <span class="help-block">
                    <strong>{{ $errors->first('package_code') }}</strong>
                </span>
            @endif
        </div>
    </div>

    @include('partials._adult-child-controls')
    @include('agent.home._form-submit')

</form>