<div class="_widget _price">
    <h3 class="_widget__title">City Area</h3>
    <form action="" class="form-horizontal">
        @foreach ($available_cities as $_city)
           <div class="row">
                <div class="col-xs-2">
                    <input type="checkbox" name="filter_city[]" value="{{ $_city['name'] }}"
                        {{ is_array(Request::get('filter_city')) && in_array($_city['name'], Request::get('filter_city')) ? 'checked' : '' }}
                    />
                </div>
                <div class="col-xs-10">
                    <span>{{ $_city['name'] }}</span>
                    <span class="pull-right">{{ $_city['count'] }}</span>
                </div>
            </div>
        @endforeach
        <div class="_action">
            <button class="btn btn-primary" type="submit">Apply</button>
            <button class="btn btn-info" type="reset">Reset</button>
        </div>
    </form>
</div>