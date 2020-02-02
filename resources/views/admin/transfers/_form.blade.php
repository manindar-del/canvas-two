<div class="panel panel-default">

    <div class="panel-heading">{{ isset($transfer) ? 'Update Transfer' : 'New Transfer' }}</div>

    <div class="panel-body">

        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="title" class="col-md-4 control-label">Title</label>
            <div class="col-md-6">
                <input id="title" type="text" class="form-control" name="title" value="{{ $transfer->title ?? old('title') }}">
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            <label for="type" class="col-md-4 control-label">Type</label>
            <div class="col-md-6">
                <select name="type" id="type" name="type" class="form-control select-2">
                    <option value="">Select</option>
                    @foreach (['airport' => 'Airport', 'local' => 'Local', 'ferry' => 'Ferry', 'combo' => 'Combo'] as $_key => $_value)
                        <option value="{{ $_key }}"
                            {{ !empty($transfer) && $transfer->type == $_key ? 'selected' : ( old('type') == $_key ? 'selected' : '' ) }}
                        >{{ $_value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
            <label for="country_id" class="col-md-4 control-label">Country</label>
            <div class="col-md-6">
                <select name="country_id" name="country_id" class="form-control select-2">
                    <option value="">Select</option>
                    @foreach ($country as $_country)
                        @if (!empty($transfer->country_id) && $transfer->country_id == $_country->code)
                            <option value="{{ $_country->code }}" data-id="{{ $_country->code }}" selected>{{ $_country->name }}</option>
                        @else
                            <option value="{{ $_country->code }}" data-id="{{ $_country->code }}">{{ $_country->name }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('country_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('country_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label for="city" class="col-md-4 control-label">City</label>

            <div class="col-md-6">
                <select name="city" id="city"  class="form-control select-2">
                    <option value="">Select</option>
                    @foreach ($cities as $_city)
                        <option value="{{ $_city->id }}" data-country-code="{{ $_city->country_code }}"
                            {{ !empty($transfer) && $_city->id == $transfer->city_id ? 'selected' : '' }}
                        >{{ $_city->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('no_of_adult') ? ' has-error' : '' }}">
            <label for="no_of_adult" class="col-md-4 control-label">Max No of Adult</label>
            <div class="col-md-6">
                <select name="no_of_adult" id="no_of_adult" name="no_of_adult" class="form-control select-2">
                    @foreach (range(0, 100) as $_range)
                        @if (!empty($transfer->no_of_adult) && $transfer->no_of_adult == $_range)
                            <option value="{{ $_range }}" selected>{{ $_range }}</option>
                        @else
                            <option value="{{ $_range }}">{{ $_range }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('no_of_adult'))
                    <span class="help-block">
                        <strong>{{ $errors->first('no_of_adult') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('no_of_child') ? ' has-error' : '' }}">
            <label for="no_of_child" class="col-md-4 control-label">Max No of Child</label>
            <div class="col-md-6">
                <select name="no_of_child" id="no_of_child" name="no_of_child" class="form-control select-2">
                    @foreach (range(0, 100) as $_range)
                        @if (!empty($transfer->no_of_child) && $transfer->no_of_child == $_range)
                            <option value="{{ $_range }}" selected>{{ $_range }}</option>
                        @else
                            <option value="{{ $_range }}">{{ $_range }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('no_of_child'))
                    <span class="help-block">
                        <strong>{{ $errors->first('no_of_child') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
            <label for="details" class="col-md-4 control-label">Details</label>
            <div class="col-md-6">
                <textarea id="details" type="text" class="form-control" name="details">{{ $transfer->details ?? old('details') }}</textarea>
                @if ($errors->has('details'))
                    <span class="help-block">
                        <strong>{{ $errors->first('details') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('featured_image') ? ' has-error' : '' }}">
            <label for="featured_image" class="col-md-4 control-label">Featured Image</label>
            <div class="col-md-6">
                <input id="featured_image" type="file" class="form-control" name="featured_image" value="">
                @if ($errors->has('featured_image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('featured_image') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="gallery_image" class="col-md-4 control-label">Gallery Images</label>
            <div class="col-md-6">
                <div class="input-group increment" >
                    <input type="file" name="filename[]" class="form-control">
                    <div class="input-group-btn">
                        <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 clone hide">
                <div class="control-group input-group" style="margin-top:10px">
                    <input type="file" name="filename[]" class="form-control">
                    <div class="input-group-btn">
                        <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($transfer->gallery_image))
            <hr>
            <div class="row">
                @foreach ($transfer->gallery_image as $_gallery_image)
                    <div class="col-md-3 gallery"> <img src="{{  asset('storage/' . $_gallery_image) }}" />
                        <input type="hidden" name="filename_hidden[]" value="{{$_gallery_image}}">
                        <a href="#" class="remove_gallery">Remove</a>
                    </div>
                @endforeach
            </div>
            <hr>
        @endif

        <div class="form-group">
            <label for="featured_image" class="col-md-4 control-label">Unit Price</label>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        Adult
                        <br>
                        <input  type="text" class="form-control" name="adult_price" value="{{ $transfer->adult_price ?? old('adult_price') }}">
                    </div>
                    <div class="col-md-6">
                        Child
                        <br>
                        <input  type="text" class="form-control" name="child_price" value="{{ $transfer->child_price ?? old('child_price') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="form-group">
            <label for="featured_image" class="col-md-4 control-label">Allow</label>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        @if(!empty($transfer->adult_allowed) && $transfer->adult_allowed == "Yes")
                            <input  type="checkbox" checked  name="adult_allowed" value="Yes"> Adult
                        @else
                            <input  type="checkbox" name="adult_allowed" value="Yes"> Adult
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if(!empty($transfer->child_allowed) && $transfer->child_allowed == "Yes")
                            <input  type="checkbox" checked  name="child_allowed" value="Yes"> Child
                        @else
                            <input  type="checkbox" name="child_allowed" value="Yes"> Child
                        @endif
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="form-group">
            <label for="gallery_image" class="col-md-4 control-label">
                <button class="btn btn-info add_field_button">Add More Fields</button>
            </label>
            <div class="col-md-8">
                <div class="row">
                    <div class="input_fields_wrap">

                        <div class="input_fields_wrap_clone">
                            <div class="col-md-4">
                                Cancellation  Days
                                @if(!empty($transfer->cancellation_date->cancellation_date))
                                    @php
                                        $_gdata = array_shift($transfer->cancellation_date->cancellation_date);
                                    @endphp
                                    <input id="cancellation_date" type="text" class="form-control" name="cancellation_date[]" value="{{ $_gdata }}" />
                                @else
                                    <input id="cancellation_date" type="text" class="form-control" name="cancellation_date[]" value="" />
                                @endif
                            </div>

                            <div class="col-md-4">
                                Adult (%)
                                @if(!empty($transfer->cancellation_date->adult_amount))
                                    @php
                                        $_adult_amount = array_shift($transfer->cancellation_date->adult_amount);
                                    @endphp
                                    <input type="text" class="form-control" name="adult_amount[]" value="{{ $_adult_amount }}">
                                @else
                                    <input type="text" class="form-control" name="adult_amount[]" value="">
                                @endif
                            </div>

                            <div class="col-md-4">
                                Child (%)
                                @if(!empty($transfer->cancellation_date->child_amount))
                                    @php
                                        $_child_amount = array_shift($transfer->cancellation_date->child_amount);
                                    @endphp
                                    <input  type="text" class="form-control" name="child_amount[]" value="{{ $_child_amount }}">
                                @else
                                    <input  type="text" class="form-control" name="child_amount[]" value="">
                                @endif
                            </div>
                        </div>

                        @if(!empty($transfer->cancellation_date->cancellation_date))
                            @foreach ($transfer->cancellation_date->cancellation_date as $_index => $_gdata)
                                @php
                                    if (0 == $_index) {
                                        continue;
                                    }
                                @endphp
                                <div class="input_fields_wrap_clone">
                                    <div class="col-md-3">
                                        @if (!empty($_gdata))
                                            <input id="cancellation_date" type="text" class="form-control" name="cancellation_date[]" value="{{ $_gdata }}" />
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if (!empty($transfer->cancellation_date->adult_amount[$_index]))
                                            <input type="text" class="form-control" name="adult_amount[]" value="{{ $transfer->cancellation_date->adult_amount[$_index] }}">
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if (!empty($transfer->cancellation_date->child_amount[$_index]))
                                            <input type="text" class="form-control" name="child_amount[]" value="{{ $transfer->cancellation_date->child_amount[$_index] }}">
                                        @endif
                                    </div>
                                    <a href="#" class="col-md-3 remove_field">Remove</a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div> --}}

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-md btn-primary">Save</button>
            </div>
        </div>

    </div>
</div>

 @push('footer-bottom')

    <script>
        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div class="input_fields_wrap_clone"><div class="col-md-3"><input id="cancellation_date'+x+'" type="text" class="form-control" name="cancellation_date[]" value="" /></div> <div class="col-md-3"><input id="type" type="text" class="form-control" name="adult_amount[]"></div> <div class="col-md-3"><input id="type" type="text" class="form-control" name="child_amount[]"></div> <a href="#" class="remove_field">Remove</a></div>'); //add input box

                jQuery('.date-picker').datetimepicker({
                    format: 'DD/MM/YYYY'
                });
            }
        });

        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        });
    </script>

    {{-- <script>
        jQuery(document).on('change', '.country', function(e) {
            let $country = jQuery(this);
            let $form = $country.parents('form');
            let $city = $form.find('.city');
            let $city_hidden = $form.find('.city_hidden');
            let $options = $city.find('option');
           // let $selected = $city.find('selected'); alert(selected);
            $options.hide();
            $options.each(function(index) {
                let $this = jQuery(this);
                if ($this.data('country-code') == $country.val()) {
                    $this.show();
                }
            });
            $city.val('');
        });
        jQuery('.country').change();
    </script> --}}

    <script type="text/javascript">
        $(document).ready(function() {
            var city =  $('#city').val();
        });
    </script>

    <script type="text/javascript">
        $(".btn-success").click(function(){
            var html = $(".clone").html();
            $(".increment").after(html);
        });

        $("body").on("click",".btn-danger",function(){
            $(this).parents(".control-group").remove();
        });

        $("body").on("click",".remove_gallery",function(){
            $(this).parents(".gallery").remove();
        });
    </script>

 @endpush