<div class="panel panel-default">

    <div class="panel-heading">{{ isset($tour) ? 'Update Tour' : 'New Tour' }}</div>

    <div class="panel-body">

        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="title" class="col-md-4 control-label">Title</label>

            <div class="col-md-6">
                <input id="title" type="text" class="form-control" name="title" value="{{ $tour->title or old('title') }}">

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
                <input id="type" type="text" class="form-control" name="type" value="{{ $tour->type or old('type') }}">

                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
            <label for="country_id" class="col-md-4 control-label">Country</label>

            <div class="col-md-6">
            <select name="country_id" id="country_id" name="country_id" class="form-control country select-2">
                <option value="">Select</option>
                @foreach ($country as $_country)
                    <option value="{{ $_country->code }}" data-id="{{ $_country->code }}"
                    {{ old('country_id') == $_country->code ? 'selected' : '' or $tour->country_id == $_country->code ? 'selected' : '' }}
                    
                     >{{ $_country->name }}</option>
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
                <select name="city" id="city"  class="form-control  city select-2">
                    <option value="">Select</option>
                    @foreach ($cities as $_city)
                        <option value="{{ $_city->id }}" data-country-code="{{ $_city->country_code }}"
                            {{ $tour->city_id == $_city->id ? 'selected' : '' }}
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

        <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
            <label for="start_time" class="col-md-4 control-label">Start Time</label>

            <div class="col-md-6">
                <select name="start_time" id="start_time" n class="form-control  select-2">
                    <option value="">Select</option>
                    <?php
                    $start = strtotime('12:00 AM');
                    $end   = strtotime('11:59 PM');
                    ?>

                <?php for($i = $start;$i<=$end;$i+=1800){ ?>
                    <option value='<?php echo date('G:i', $i); ?>'  {{ $tour->start_time == date('G:i', $i) ? 'selected' : '' }}><?php echo date('G:i', $i); ?></option>;
                <?php } ?>

                </select>

                @if ($errors->has('start_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('start_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('end_time') ? ' has-error' : '' }}">
            <label for="end_time" class="col-md-4 control-label">End Time</label>

            <div class="col-md-6">
                <select name="end_time" id="end_time" n class="form-control  select-2">
                    <option value="">Select</option>
                    <?php
                    $start = strtotime('12:00 AM');
                    $end   = strtotime('11:59 PM');
                    ?>

                <?php for($i = $start;$i<=$end;$i+=1800){ ?>
                    <option value='<?php echo date('G:i', $i); ?>'  {{ $tour->end_time == date('G:i', $i) ? 'selected' : '' }}><?php echo date('G:i', $i); ?></option>;
                <?php } ?>

                </select>

                @if ($errors->has('end_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('end_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

<div class="form-group{{ $errors->has('no_of_adult') ? ' has-error' : '' }}">
        <label for="no_of_adult" class="col-md-4 control-label">No of Adult</label>

        <div class="col-md-6">
            <select name="no_of_adult" id="no_of_adult" name="no_of_adult" class="form-control select-2">
                @foreach (range(1, 9) as $_range)
                    <option value="{{ $_range }}" {{ $tour->no_of_adult == $_range ? 'selected' : '' }}>{{ $_range }}</option>
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
        <label for="no_of_child" class="col-md-4 control-label">No of Child</label>

        <div class="col-md-6">
            <select name="no_of_child" id="no_of_child" name="no_of_child" class="form-control select-2">
                @foreach (range(1, 9) as $_range)
                    <option value="{{ $_range }}" {{ $tour->no_of_child == $_range ? 'selected' : '' }}>{{ $_range }}</option>
                @endforeach
            </select>

            @if ($errors->has('no_of_child'))
                <span class="help-block">
                    <strong>{{ $errors->first('no_of_child') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('no_of_infant') ? ' has-error' : '' }}">
        <label for="no_of_infant" class="col-md-4 control-label">No of Infant</label>

        <div class="col-md-6">
            <select name="no_of_infant" id="no_of_infant" name="no_of_infant" class="form-control select-2">
                @foreach (range(1, 9) as $_range)
                    <option value="{{ $_range }}" {{ $tour->no_of_infant == $_range ? 'selected' : '' }}>{{ $_range }}</option>
                @endforeach
            </select>

            @if ($errors->has('no_of_infant'))
                <span class="help-block">
                    <strong>{{ $errors->first('no_of_infant') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
            <label for="details" class="col-md-4 control-label">Details</label>

            <div class="col-md-6">
            <textarea id="details" type="text" class="form-control" name="details">{{ $tour->details or old('details') }}</textarea>
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
            <div class="input-group form-group increment" >
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
        @php
    //dd($tour->cancellation_date->adult_amount);
    foreach ($tour->cancellation_date->adult_amount as $_gdata) {
//print_r($_gdata);
       // echo $_gdata->cancellation_date;
        }
    @endphp
        <div class="form-group">
        <label for="gallery_image" class="col-md-4 control-label">  <button class="add_field_button">Add More Fields</button></label>
                <div class="col-md-8">

                <div class="input_fields_wrap">
                <div class="col-md-3">
                Cancellation  Date
                @php
                foreach ($tour->cancellation_date->cancellation_date as $_gdata) {
                @endphp
                    <input id="cancellation_date" type="text" class="form-control date-picker" name="cancellation_date[]" value="{{ $_gdata }}" />
                @php
                }
                @endphp   

                </div>
                <div class="col-md-3">
                Adult Amount
                @php
                foreach ($tour->cancellation_date->adult_amount as $_adult_amount) {
                @endphp
                        <input type="text" class="form-control" name="adult_amount[]" value="{{ $_adult_amount }}">
                @php
                }
                @endphp  
                
                </div>
                <div class="col-md-3">
                Child Amount
                @php
                foreach ($tour->cancellation_date->child_amount as $_child_amount) {
                @endphp
                        <input  type="text" class="form-control" name="child_amount[]" value="{{ $_child_amount }}">
                @php
                }
                @endphp 
                </div>
                <div class="col-md-3">
                Infant Amount
                @php
                foreach ($tour->cancellation_date->infant_amount as $_infant_amount) {
                @endphp
                        <input type="text" class="form-control" name="infant_amount[]" value="{{ $_infant_amount }}">
                @php
                }
                @endphp 
                </div>




                </div>
                </div>
        </div>




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
            $(wrapper).append('<div><div class="col-md-3"><input id="cancellation_date'+x+'" type="text" class="form-control date-picker" name="cancellation_date[]" value="{{ date('d/m/Y') }}" /></div> <div class="col-md-3"><input id="type" type="text" class="form-control" name="adult_amount[]"></div> <div class="col-md-3"><input id="type" type="text" class="form-control" name="child_amount[]"></div> <div class="col-md-3"><input id="type" type="text" class="form-control" name="infant_amount[]"></div><a href="#" class="remove_field">Remove</a></div>'); //add input box

            jQuery('.date-picker').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })

  </script>

    <script>
        jQuery(document).on('change', '.country', function(e) {
            let $country = jQuery(this);
            let $form = $country.parents('form');
            let $city = $form.find('.city');
            let $options = $city.find('option');
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
    </script>

    <script type="text/javascript">
            $(".btn-success").click(function(){
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click",".btn-danger",function(){
                $(this).parents(".control-group").remove();
            });

    </script>
 @endpush