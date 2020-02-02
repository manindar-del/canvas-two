@extends('layouts.admin')

@section('content')
<div class="section_type">
  <div class="_page">
     <div class="container-fluid">

          @include('partials._info')
           @include('partials._errors')

            <table class="table table-stripped" id="type"style="display: none;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th class="table__actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($types as $_type)
                        <tr>
                            <td>{{ $_type->id }}</td>
                            <td>{{ $_type->name }}</td>
                            <td>{{ $_type->value }}</td>
                            <td class="table__actions" style="width: 260px;">
                                <a href="{{ route('admin.type.edit', [$_type->id]) }}" class="btn btn-sm btn-info">
                                    Edit
                                </a>
                                <a href="#" onClick="event.preventDefault(); confirm() && form{{ $_type->id }}.submit()" class="btn btn-sm btn-danger">
                                        {{-- <i class="fa fa-trash"></i>  --}}
                                        Delete
                                    </a>
                                    <form action="{{ route('admin.type.destroy', [$_type->id]) }}" method="post" id="form{{ $_type->id }}">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                </form>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('footer-bottom')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/nouislider.min.js') }}"></script>
<script>
    jQuery('#type').dataTable({
        sort: [],
        responsive: true
    }).show();
</script>
@endpush


