<div class="_card">
    <div class="_card__body">
        <h4 class="mb-5">Financial Information: {{ Auth::user()->currency }}</h4>
        <div class="row">
            <div class="col-xs-8"><p>Credit Limit:</p></div>
            <div class="col-xs-4 text-right">{{ Auth::user()->total_wallet_balance }}</div>
        </div>
        <div class="row">
            <div class="col-xs-8"><p>Credit Used:</p></div>
            <div class="col-xs-4 text-right">{{ Auth::user()->total_spent }}</div>
        </div>
        <div class="row">
            <div class="col-xs-8"><p>Refund Amount:</p></div>
            <div class="col-xs-4 text-right">{{ Auth::user()->refund_balance  }}</div>
        </div>
        <div class="row">
            <div class="col-xs-8"><p>Credit Balance:</p></div>
            <div class="col-xs-4 text-right">{{ Auth::user()->available_wallet_balance }}</div>
        </div>
    </div>
</div>