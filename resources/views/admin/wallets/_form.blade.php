<div class="panel panel-default">

    <div class="panel-heading">{{ $title }}</div>

    <div class="panel-body">

        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
            <label for="amount" class="col-md-4 control-label">Amount</label>

            <div class="col-md-6">
                <input id="amount" type="text" class="form-control" name="amount" value="{{ $wallet->amount ?? old('amount') }}">

                @if ($errors->has('amount'))
                    <span class="help-block">
                        <strong>{{ $errors->first('amount') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-md btn-primary">Save</button>
                {{ csrf_field() }}
            </div>
        </div>

    </div>

</div>

{{--
<hr>

<div class="row">
    <div class="col-sm-11 col-sm-offset-1">
        <a class="btn btn-info" href="{{ route('agents.index') }}">Back to All Agents</a>
        <a class="btn btn-info" href="{{ route('agents.wallets.index', [$agent->id]) }}">Back to Balances</a>
    </div>
</div> --}}