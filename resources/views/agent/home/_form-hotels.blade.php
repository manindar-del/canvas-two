<form class="form-horizontal" method="POST" action="{{ route('hotels.search') }}" id="searchForm">

    <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
        <label for="nationality" class="col-md-4 control-label">Nationality</label>

        <div class="col-md-7">
            <select name="nationality" id="nationality" name="nationality" class="form-control select-2">
                @foreach ($nationalities as $_nationality)
                    <option
                        value="{{ $_nationality->country_code }}"
                        {{ old('nationality') == $_nationality->country_code || 'IN' == $_nationality->country_code ? 'selected' : '' }}
                    >{{ $_nationality->name }}</option>
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

        <div class="col-md-7">
            <select name="city" id="city" name="city" class="form-control select-2">
                @foreach ($cities as $_city)
                    <option value="{{ $_city->id }}" {{ old('city') == $_city->name ? 'selected' : '' }}>{{ $_city->name }}, {{ $_city->country->name }}</option>
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

        <div class="col-md-7">
            <input id="check_in" type="text" class="form-control datepicker2 check_in" name="check_in" value="" />

            @if ($errors->has('check_in'))
                <span class="help-block">
                    <strong>{{ $errors->first('check_in') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('check_out') ? ' has-error' : '' }}">
        <label for="check_out" class="col-md-4 control-label">Check Out</label>

        <div class="col-md-7">
            <input id="check_out" type="text" class="form-control datepicker2 check_out" name="check_out" value="" />

            @if ($errors->has('check_out'))
                <span class="help-block">
                    <strong>{{ $errors->first('check_out') }}</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- <div class="form-group{{ $errors->has('special_request') ? ' has-error' : '' }}">
        <label for="special_request" class="col-md-4 control-label">Special Request</label>
        <div class="col-md-7">
            <textarea name="special_request" class="form-control">{{ old('special_request') }}</textarea>
            @if ($errors->has('special_request'))
                <span class="help-block">
                    <strong>{{ $errors->first('special_request') }}</strong>
                </span>
            @endif
        </div>
    </div> --}}

    {{-- <div class="form-group{{ $errors->has('star_rating') ? ' has-error' : '' }}">
        <label for="star_rating" class="col-md-4 control-label">Star Rating</label>

        <div class="col-md-7">
            <select name="star_rating" id="star_rating" name="star_rating" class="form-control">
                <option value="">Select</option>
                @foreach (range(1, 5) as $_rating)
                    <option value="{{ $_rating }}" {{ old('star_rating') == $_rating ? 'selected' : '' }}>{{ $_rating }}</option>
                @endforeach
            </select>

            @if ($errors->has('star_rating'))
                <span class="help-block">
                    <strong>{{ $errors->first('star_rating') }}</strong>
                </span>
            @endif
        </div>
    </div> --}}

    <input type="hidden" name="star_rating" value="" />

    {{-- <div class="form-group{{ $errors->has('nights') ? ' has-error' : '' }}">
        <label for="nights" class="col-md-4 control-label">Nights</label>

        <div class="col-md-7">
            <select name="nights" id="nights" name="nights" class="form-control">
                @foreach (range(1, 21) as $_rating)
                    <option value="{{ $_rating }}" {{ old('nights') == $_rating ? 'selected' : '' }}>{{ $_rating }}</option>
                @endforeach
            </select>

            @if ($errors->has('nights'))
                <span class="help-block">
                    <strong>{{ $errors->first('nights') }}</strong>
                </span>
            @endif
        </div>
    </div> --}}

    <div class="form-group{{ $errors->has('rooms') ? ' has-error' : '' }}">
        <label for="rooms" class="col-md-4 control-label">Rooms</label>

        <div class="col-md-7">
            <select name="rooms" id="rooms" name="rooms" class="form-control noOfRooms">
                @foreach (range(1, 2) as $_rating)
                    <option value="{{ $_rating }}" {{ old('rooms') == $_rating ? 'selected' : '' }}>{{ $_rating }}</option>
                @endforeach
            </select>

            @if ($errors->has('rooms'))
                <span class="help-block">
                    <strong>{{ $errors->first('rooms') }}</strong>
                </span>
            @endif
        </div>
    </div>

    @for ($i = 0; $i < 3; $i++)
        <div class="roomGuests">
            @include('partials._adult-child-controls')
        </div>
    @endfor


    {{-- <hr />

    <label class="control-label">Advanced Search</label>

    <br>
    <br>
    <br> --}}

    {{-- <div class="form-group{{ $errors->has('hotel_name') ? ' has-error' : '' }}">
        <label for="hotel_name" class="col-md-4 control-label">Hotel Name</label>

        <div class="col-md-7">
            <input id="hotel_name" type="text" class="form-control" name="hotel_name" value="{{ old('hotel_name') }}" />

            @if ($errors->has('hotel_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('hotel_name') }}</strong>
                </span>
            @endif
        </div>
    </div> --}}

    <input type="hidden" name="currency" value="{{ Auth::user()->currency }}" />

    @include('agent.home._form-submit')

</form>