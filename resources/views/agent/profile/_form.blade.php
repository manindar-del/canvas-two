<div class="panel panel-default">

    <div class="panel-heading">My profile</div>

    <div class="panel-body">
    <form  class="form-horizontal" method="post"  action="{{ route('profile.update') }}">
    <input type="hidden" name="id" value="{{{ Auth::user()->id }}}"> 
        {{ csrf_field() }}

        <div class="form-group">
            <label for="name" class="col-md-4 control-label">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
               
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-md-4 control-label">Tel</label>

            <div class="col-md-6">
                <input id="tel" type="text" class="form-control" name="tel" value="{{ Auth::user()->tel }}">
               
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-md-4 control-label">Address</label>

            <div class="col-md-6">
            <textarea type="text" class="form-control" name="address">{{ Auth::user()->address }}</textarea>
               
            </div>
        </div>

        

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-md btn-primary">Update</button>
            </div>
        </div>
        </form>

    </div>

</div>