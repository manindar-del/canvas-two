<div class="_widget search">
    <h3 class="_widget__title">Search</h3>
    <form class="form-horizontal" method="POST" action="{{ route('hotels.search') }}">

        <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
            <label for="nationality" class="col-md-4 control-label">Nationality</label>

            <div class="col-md-8">
                <select name="nationality" id="nationality" name="nationality" class="form-control">
                    @foreach ($nationalities as $_nationality)
                        <option value="{{ $_nationality->country_code }}" {{ $form['nationality'] == $_nationality->country_code ? 'selected' : '' }}>{{ $_nationality->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('nationality'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nationality') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label for="city" class="col-md-4 control-label">Destination</label>

            <div class="col-md-8">
                <select name="city" id="city" name="city" class="form-control">
                    @foreach ($cities as $_city)
                        <option value="{{ $_city->id }}" {{ $form['city'] == $_city->name ? 'selected' : '' }}>{{ $_city->name }}, {{ $_city->country->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('check_in') ? ' has-error' : '' }}">
            <label for="check_in" class="col-md-4 control-label">Check In</label>

            <div class="col-md-8">
                <input id="check_in" type="text" class="form-control datepicker" name="check_in" value="{{ $form['check_in'] }}" />

                @if ($errors->has('check_in'))
                    <span class="help-block">
                        <strong>{{ $errors->first('check_in') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('check_out') ? ' has-error' : '' }}">
            <label for="check_out" class="col-md-4 control-label">Check Out</label>

            <div class="col-md-8">
                <input id="check_out" type="text" class="form-control datepicker" name="check_out" value="{{ $form['check_out'] }}" />

                @if ($errors->has('check_out'))
                    <span class="help-block">
                        <strong>{{ $errors->first('check_out') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('nights') ? ' has-error' : '' }}">
            <label for="nights" class="col-md-4 control-label">Nights</label>

            <div class="col-md-8">
                <select name="nights" id="nights" name="nights" class="form-control">
                    @foreach (range(1, 21) as $_rating)
                        <option value="{{ $_rating }}" {{ $form['nights'] == $_rating ? 'selected' : '' }}>{{ $_rating }}</option>
                    @endforeach
                </select>

                @if ($errors->has('nights'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nights') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('rooms') ? ' has-error' : '' }}">
            <label for="rooms" class="col-md-4 control-label">Rooms</label>

            <div class="col-md-8">
                <select name="rooms" id="rooms" name="rooms" class="form-control">
                    @foreach (range(1, 9) as $_rating)
                        <option value="{{ $_rating }}" {{ $form['rooms'] == $_rating ? 'selected' : '' }}>{{ $_rating }}</option>
                    @endforeach
                </select>

                @if ($errors->has('rooms'))
                    <span class="help-block">
                        <strong>{{ $errors->first('rooms') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        @include('partials._adult-child-controls')

        <input type="hidden" name="currency" value="INR" />

        @include('agent.home._form-submit')

    </form>
</div>