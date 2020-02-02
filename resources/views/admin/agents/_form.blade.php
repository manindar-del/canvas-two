
<div class="panel panel-default">

    <div class="panel-heading">{{ isset($agent) ? 'Update Agent' : 'New Agent' }}</div>

    <div class="panel-body">

        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Name*</label>
            <div class="col-md-5">
                <input id="name" type="text" class="form-control" name="name" value="{{ $agent->name ?? old('name') }}" required />
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
            <label for="user_name" class="col-md-4 control-label">User Name</label>

            <div class="col-md-5">
                <input id="user_name" type="text" class="form-control" name="user_name" value="{{ $agent->user_name ?? old('user_name') }}">

                @if ($errors->has('user_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('user_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        {{-- <div class="form-group{{ $errors->has('agent_code') ? ' has-error' : '' }}">
            <label for="agent_code" class="col-md-4 control-label">Agent Code</label>

            <div class="col-md-5">
                <input id="agent_code" type="text" class="form-control" name="agent_code" value="{{ $agent->agent_code ?? old('agent_code') }}">

                @if ($errors->has('agent_code'))
                    <span class="help-block">
                        <strong>{{ $errors->first('agent_code') }}</strong>
                    </span>
                @endif
            </div>
        </div> --}}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-5">
                <input id="email" type="email" class="form-control" name="email" value="{{ $agent->email ?? old('email') }}" required autofocus >

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-5">
                <input id="password" type="password" class="form-control" name="password" value="">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password_confirmation" class="col-md-4 control-label">Password Confirmation</label>

            <div class="col-md-5">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" value="">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
            <label for="company_name" class="col-md-4 control-label">Company Name</label>
            <div class="col-md-5">
                <input id="company_name" type="text" class="form-control" name="company_name" value="{{ $agent->company_name ?? old('company_name') }}" />

                @if ($errors->has('company_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('company_address') ? ' has-error' : '' }}">
            <label for="company_address" class="col-md-4 control-label">Company Address</label>
            <div class="col-md-5">
                <textarea id="company_address" type="text" class="form-control" name="company_address">{{ $agent->company_address ?? old('company_address') }}</textarea>
                @if ($errors->has('company_address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company_address') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
            <label for="country" class="col-md-4 control-label">Country</label>
            <div class="col-md-5">
                <select name="country" class="form-control">
                    <option value="" selected="selected">-- Choose One --</option>
                    @foreach ($countries as $_country)
                        <option value="{{ $_country->code }}" {{ (!empty($agent) && $agent->country == $_country->code) || old('country') == $_country->code ? 'selected' : '' }}>{{ $_country->name }}</option>
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
            <label for="city" class="col-md-4 control-label">City</label>
            <div class="col-md-5">
                <select name="city" class="form-control">
                    <option value="" selected="selected">-- Choose One --</option>
                    @foreach ($cities as $_city)
                        <option value="{{ $_city->id }}" {{ (!empty($agent) && $agent->city == $_city->id) || old('city') == $_city->id ? 'selected' : '' }}>{{ $_city->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <label for="phone" class="col-md-4 control-label">Phone</label>
            <div class="col-md-5">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ $agent->phone ?? old('phone') }}" />

                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
            <label for="fax" class="col-md-4 control-label">Fax</label>
            <div class="col-md-5">
                <input id="fax" type="text" class="form-control" name="fax" value="{{ $agent->fax ?? old('fax') }}" />

                @if ($errors->has('fax'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fax') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
            <label for="website" class="col-md-4 control-label">Website</label>
            <div class="col-md-5">
                <input id="website" type="text" class="form-control" name="website" value="{{ $agent->website ?? old('website') }}" />

                @if ($errors->has('website'))
                    <span class="help-block">
                        <strong>{{ $errors->first('website') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('salutation') ? ' has-error' : '' }}">
            <label for="salutation" class="col-md-4 control-label">Salutation</label>
            <div class="col-md-5">
                {{-- <input id="salutation" type="text" class="form-control" name="salutation" value="{{ old('salutation') }}" required autofocus> --}}
                <select selected name="salutation" class="form-control">
                    <option value="">-- Choose One --</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'mr') || old('salutation') == 'mr' ? 'selected' : '' }} value="mr">Mr.</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'ms') || old('salutation') == 'ms' ? 'selected' : '' }} value="ms">Ms.</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'mrs') || old('salutation') == 'mrs' ? 'selected' : '' }} value="mrs">Mrs.</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'miss') || old('salutation') == 'miss' ? 'selected' : '' }} value="miss">Miss</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'dr') || old('salutation') == 'dr' ? 'selected' : '' }} value="dr">Dr.</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'madam') || old('salutation') == 'madam' ? 'selected' : '' }} value="madam">Madam</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'sir') || old('salutation') == 'sir' ? 'selected' : '' }} value="sir">Sir</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'sir') || old('salutation') == 'sir_madam' ? 'selected' : '' }} value="sir_madam">Sir/Madam</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'child') || old('salutation') == 'child' ? 'selected' : '' }} value="child">Child</option>
                    <option {{ (!empty($agent) && $agent->salutation == 'messrs') || old('salutation') == 'messrs' ? 'selected' : '' }} value="messrs">Messrs.</option></select>
                </select>

                @if ($errors->has('salutation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('salutation') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
            <label for="position" class="col-md-4 control-label">Position</label>
            <div class="col-md-5">
                <input id="position" type="text" class="form-control" name="position" value="{{ $agent->position ?? old('position') }}" />

                @if ($errors->has('position'))
                    <span class="help-block">
                        <strong>{{ $errors->first('position') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        {{-- <div class="form-group{{ $errors->has('hotel_hike') ? ' has-error' : '' }}">
            <label for="hotel_hike" class="col-md-4 control-label">Hotel Price Hike</label>

            <div class="col-md-5">
                <input id="hotel_hike" type="text" class="form-control" name="hotel_hike" value="{{ $agent->hotel_hike ?? old('hotel_hike') }}" />

                @if ($errors->has('hotel_hike'))
                    <span class="help-block">
                        <strong>{{ $errors->first('hotel_hike') }}</strong>
                    </span>
                @endif
            </div>
        </div> --}}

        <input type="hidden" name="hotel_hike" value="{{ $agent->hotel_hike ?? old('hotel_hike') }}" />

        <div class="form-group{{ $errors->has('tour_hike') ? ' has-error' : '' }}">
            <label for="tour_hike" class="col-md-4 control-label">Tour Price Hike</label>

            <div class="col-md-5">
                <input id="tour_hike" type="text" class="form-control" name="tour_hike" value="{{ $agent->tour_hike ?? old('tour_hike') }}" />

                @if ($errors->has('tour_hike'))
                    <span class="help-block">
                        <strong>{{ $errors->first('tour_hike') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('transfer_hike') ? ' has-error' : '' }}">
            <label for="transfer_hike" class="col-md-4 control-label">Transfer Price Hike</label>

            <div class="col-md-5">
                <input id="transfer_hike" type="text" class="form-control" name="transfer_hike" value="{{ $agent->transfer_hike ?? old('transfer_hike') }}" />

                @if ($errors->has('transfer_hike'))
                    <span class="help-block">
                        <strong>{{ $errors->first('transfer_hike') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-5 col-md-offset-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="is_active" {{ (!empty($agent) ? $agent->is_active : old('is_active'))  ? 'checked' : '' }}>is Active?
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-5 col-md-offset-4">
                <button type="submit" class="btn btn-md btn-primary">Save</button>
            </div>
        </div>

    </div>

</div>