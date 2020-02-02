<div class="row">
    <div class="col-sm-4">
        {{--  --}}
    </div>
    <div class="col-sm-7">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                    <label for="adult" class="col-xs-12 control-label">
                        <div class="text-center">Adult</div>
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                    <label for="adult" class="col-xs-12 control-label">
                        <div class="text-center">Child</div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="control-label text-right">Room #{{ ($i + 1) }}</div>
    </div>
    <div class="col-sm-7">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('adult') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <select id="adult" name="adult[{{$i}}]" class="form-control">
                            @foreach (range(1, 3) as $_count)
                                <option value="{{ $_count }}" {{ old('adult') == $_count ? 'selected' : '' }}>{{ $_count }}</option>
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
            <div class="col-sm-6">
                <div class="form-group{{ $errors->has('child') ? ' has-error' : '' }}">
                    <div class="col-xs-12">
                        <select id="child" name="child[{{$i}}]" class="form-control noOfChilds">
                            @foreach (range(0, 2) as $_count)
                                <option value="{{ $_count }}" {{ old('child') == $_count ? 'selected' : '' }}>{{ $_count }}</option>
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
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        {{--  --}}
    </div>
    <div class="col-sm-7">
        <div class="row">
            @for ($x = 0; $x < 2; $x++)
                <div class="col-xs-6 childAge">
                    <div class="form-group">
                        <label for="adult" class="col-xs-12 control-label">
                            <div class="text-center">Child {{ $x + 1 }} Age</div>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <select name="child_age[{{$i}}][]" class="form-control">
                                @for ($j = 2; $j < 13; $j++)
                                    <option value="{{ $j }}">{{ $j }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>