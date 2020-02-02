<form class="form-horizontal" method="GET" action="{{ route('tours.search') }}">

    <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
        <label for="country" class="col-md-3 control-label">Country</label>

        <div class="col-md-6">
            <select name="country" name="country" class="form-control select-2 country" style="width: 100%;">
                @foreach ($transfer_countries as $_country)
                    <option value="{{ $_country->code }}"
                        {{ (!empty($form['country']) && $form['country'] == $_country->id) || old('country') == $_country->id ? 'selected' : '' }}
                    >{{ $_country->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('country'))
                <span class="help-block">
                    <strong>{{ $errors->first('country') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        <label for="city" class="col-md-3 control-label">City</label>
        <div class="col-md-6">
            <select name="city" id="city" name="city" class="form-control select-2" style="width: 100%;">
                @foreach ($transfer_cities as $_city)
                    <option value="{{ $_city->id }}" {{ old('city') == $_city->id ? 'selected' : '' }}>{{ $_city->name }}, {{ $_city->country->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('city'))
                <span class="help-block">
                    <strong>{{ $errors->first('city') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('nationality') ? ' has-error' : '' }}">
        <label for="nationality" class="col-md-3 control-label">Nationality</label>

        <div class="col-md-6">
            <select name="nationality" id="nationality" name="nationality" class="form-control select-2">
                @foreach ($nationalities as $_nationality)
                    <option value="{{ $_nationality->country_code }}"
                        {{
                            (!empty($form['nationality']) && $form['nationality'] == $_nationality->country_code) ||
                            old('nationality') == $_nationality->country_code ||
                            'IN' == $_nationality->country_code ? 'selected' : ''
                        }}
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

    <div class="form-group{{ $errors->has('tour_date') ? ' has-error' : '' }}">
        <label for="tour_date" class="col-md-3 control-label">Tour Date</label>

        <div class="col-md-6">
            <input id="tour_date" type="text" class="form-control datepicker2" name="tour_date"
                value="{{ $form['tour_date'] ?? old('tour_date') }}"
            />

            @if ($errors->has('tour_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('tour_date') }}</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- <div class="form-group{{ $errors->has('special_request') ? ' has-error' : '' }}">
        <label for="special_request" class="col-md-3 control-label">Special Request</label>
        <div class="col-md-6">
            <textarea name="special_request" class="form-control">{{ old('special_request') }}</textarea>
            @if ($errors->has('special_request'))
                <span class="help-block">
                    <strong>{{ $errors->first('special_request') }}</strong>
                </span>
            @endif
        </div>
    </div> --}}

    <div class="row">
        <div class="col-sm-4">
            {{--  --}}
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                        <label for="adult" class="col-xs-12 control-label">
                            <div class="text-center">Adult</div>
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                        <label for="adult" class="col-xs-12 control-label">
                            <div class="text-center">Child</div>
                        </label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('infant') ? ' has-error' : '' }}">
                        <label for="infant" class="col-xs-12 control-label">
                            <div class="text-center">Infant</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <select id="adult" name="adult" class="form-control">
                                @foreach (range(1, 9) as $_count)
                                    <option value="{{ $_count }}"
                                        {{ (!empty($form['adult']) && $form['adult'] == $_count) || old('adult') == $_count ? 'selected' : '' }}
                                    >{{ $_count }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('adult'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('adult') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('child') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <select id="child" name="child" class="form-control noOfChilds">
                                @foreach (range(0, 10) as $_count)
                                    <option value="{{ $_count }}"
                                        {{ (!empty($form['child']) && $form['child'] == $_count) || old('child') == $_count ? 'selected' : '' }}
                                    >{{ $_count }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('child'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('child') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group{{ $errors->has('infant') ? ' has-error' : '' }}">
                        <div class="col-xs-12">
                            <select id="infant" name="infant" class="form-control noOfinfant">
                                @foreach (range(0, 5) as $_count)
                                    <option value="{{ $_count }}"
                                        {{ (!empty($form['infant']) && $form['infant'] == $_count) || old('infant') == $_count ? 'selected' : '' }}
                                    >{{ $_count }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('infant'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('infant') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- @include('partials._adult-child-controls') --}}
    @include('agent.home._form-submit')

</form>