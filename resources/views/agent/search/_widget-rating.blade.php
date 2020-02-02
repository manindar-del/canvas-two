<div class="_widget rating">
    <h3 class="_widget__title">Star Rating</h3>
    <form action="">
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="5" {{ Request::get('rating') && in_array(5, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <span>(5 star)</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="4" {{ Request::get('rating') && in_array(4, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <span>(4 star)</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="3" {{ Request::get('rating') && in_array(3, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <span>(3 star)</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="2" {{ Request::get('rating') && in_array(2, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <span>(2 star)</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="1" {{ Request::get('rating') && in_array(1, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <span>(1 star)</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <input type="checkbox" name="rating[]" value="0" {{ Request::get('rating') && in_array(0, Request::get('rating')) ? 'checked' : ''  }} />
            </div>
            <div class="col-xs-10">
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <span>(0 star)</span>
            </div>
        </div>
        <div class="_action">
            <button class="btn btn-primary" type="submit">Apply</button>
            <button class="btn btn-info" type="reset">Clear</button>
        </div>
    </form>
</div>