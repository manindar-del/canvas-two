@extends('layouts.agent')

@php
    $countries = \App\Country::all();
    $cities = \App\City::all();
@endphp

@section('content')
<div class="_page">
    <div class="container">

        <form class="form-horizontal _signup" method="POST" action="{{ route('register') }}">

            {{ csrf_field() }}

            {{-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Name</label>

                <div class="col-md-8">
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-8">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>

                <div class="col-md-8">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                <div class="col-md-8">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </div> --}}

            <h1 class="_signup__headline">Customer Registration</h1>

            <div class="row">

                <div class="col-sm-6">

                    {{-- <h2 class="_signup__title">Company Details</h2> --}}

                    <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                        <label for="user_name" class="col-md-4 control-label">User Name*</label>
                        <div class="col-md-8">
                            <input id="user_name" type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required autofocus />
                            @if ($errors->has('user_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">Name*</label>
                        <div class="col-md-8">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required />
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
                        <label for="company_name" class="col-md-4 control-label">Company Name</label>
                        <div class="col-md-8">
                            <input id="company_name" type="text" class="form-control" name="company_name" value="{{ old('company_name') }}" />

                            @if ($errors->has('company_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('company_address') ? ' has-error' : '' }}">
                        <label for="company_address" class="col-md-4 control-label">Company Address</label>
                        <div class="col-md-8">
                            <textarea id="company_address" type="text" class="form-control" name="company_address">{{ old('company_address') }}</textarea>
                            @if ($errors->has('company_address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('company_address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                        <label for="country" class="col-md-4 control-label">Country</label>
                        <div class="col-md-8">
                            <select name="country" class="form-control">
                                <option value="" selected="selected">-- Choose One --</option>
                                @foreach ($countries as $_country)
                                    <option value="{{ $_country->code }}" {{ old('country') == $_country->code ? 'selected' : '' }}>{{ $_country->name }}</option>
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
                        <div class="col-md-8">
                            <select name="city" class="form-control">
                                <option value="" selected="selected">-- Choose One --</option>
                                @foreach ($cities as $_city)
                                    <option value="{{ $_city->id }}" {{ old('city') == $_city->id ? 'selected' : '' }}>{{ $_city->name }}</option>
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
                        <div class="col-md-8">
                            <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" />

                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
                        <label for="fax" class="col-md-4 control-label">Fax</label>
                        <div class="col-md-8">
                            <input id="fax" type="text" class="form-control" name="fax" value="{{ old('fax') }}" />

                            @if ($errors->has('fax'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('fax') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                        <label for="website" class="col-md-4 control-label">Website</label>
                        <div class="col-md-8">
                            <input id="website" type="text" class="form-control" name="website" value="{{ old('website') }}" />

                            @if ($errors->has('website'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('website') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">

                    <div class="form-group{{ $errors->has('salutation') ? ' has-error' : '' }}">
                        <label for="salutation" class="col-md-4 control-label">Salutation</label>
                        <div class="col-md-8">
                            {{-- <input id="salutation" type="text" class="form-control" name="salutation" value="{{ old('salutation') }}" required autofocus> --}}
                            <select selected name="salutation" class="form-control">
                                <option value="">-- Choose One --</option>
                                <option {{ old('salutation') == 'mr' ? 'selected' : '' }} value="mr">Mr.</option>
                                <option {{ old('salutation') == 'ms' ? 'selected' : '' }} value="ms">Ms.</option>
                                <option {{ old('salutation') == 'mrs' ? 'selected' : '' }} value="mrs">Mrs.</option>
                                <option {{ old('salutation') == 'miss' ? 'selected' : '' }} value="miss">Miss</option>
                                <option {{ old('salutation') == 'dr' ? 'selected' : '' }} value="dr">Dr.</option>
                                <option {{ old('salutation') == 'madam' ? 'selected' : '' }} value="madam">Madam</option>
                                <option {{ old('salutation') == 'sir' ? 'selected' : '' }} value="sir">Sir</option>
                                <option {{ old('salutation') == 'sir_madam' ? 'selected' : '' }} value="sir_madam">Sir/Madam</option>
                                <option {{ old('salutation') == 'child' ? 'selected' : '' }} value="child">Child</option>
                                <option {{ old('salutation') == 'messrs' ? 'selected' : '' }} value="messrs">Messrs.</option></select>
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
                        <div class="col-md-8">
                            <input id="position" type="text" class="form-control" name="position" value="{{ old('position') }}" />

                            @if ($errors->has('position'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('position') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">E-Mail Address*</label>
                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required />

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email_confirmation') ? ' has-error' : '' }}">
                        <label for="email_confirmation" class="col-md-4 control-label"> Confirm E-mail Address*</label>
                        <div class="col-md-8">
                            <input id="email_confirmation" type="email" class="form-control" name="email_confirmation" value="{{ old('email_confirmation') }}" required />

                            @if ($errors->has('email_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Choose your Password*</label>
                        <div class="col-md-8">
                            <input id="password" type="password" class="form-control" name="password" required />

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation" class="col-md-4 control-label">Confirm Your Password*</label>
                        <div class="col-md-8">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required />

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <div class="_signup__footer">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
                {{ csrf_field() }}
            </div>

        </form>


    </div>
</div>
@endsection
