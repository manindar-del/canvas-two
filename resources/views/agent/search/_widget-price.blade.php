<div class="_widget price">
    <h3 class="_widget__title">Price Range</h3>
    <form action="">
        <div class="row">
            <div class="col-xs-12">
                <div id="priceSlider"></div>
                <input type="hidden" name="price_min" id="priceMin" value="{{ $min }}" />
                <input type="hidden" name="price_max" id="priceMax" value="{{ $max }}" />
            </div>
            <div class="col-xs-6">
                <span class="pull-left" id="priceMinLabel"></span>
            </div>
            <div class="col-xs-6">
                <span class="pull-right" id="priceMaxLabel"></span>
            </div>
        </div>
        <div class="text-center">
            <button class="btn btn-primary" type="submit">Apply</button>
        </div>
    </form>
</div>