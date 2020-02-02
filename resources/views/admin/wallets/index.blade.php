@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        <table class="table table-stripped" id="wallets" style="display: none;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Agent Name</th>
                    {{-- <th>Agent Code</th> --}}
                    <th>Amount</th>
                    <th>Date</th>
                    <th class="table__actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wallets as $_wallet)
                    <tr>
                        <td>{{ $_wallet->id }}</td>
                        <td>{{ $agent->user_name }}</td>
                        {{-- <td>{{ $agent->agent_code }}</td> --}}
                        <td>&#8377;{{ $_wallet->amount }}</td>
                        <td>{{ $_wallet->created_at }}</td>
                        <td class="table__actions">
                            <a href="{{ route('agents.wallets.edit', [$agent->id, $_wallet->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="#" onClick="event.preventDefault(); confirm() && form{{ $_wallet->id }}.submit()" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Delete
                            </a>
                            <form action="{{ route('agents.wallets.destroy', [$agent->id, $_wallet->id]) }}" method="post" id="form{{ $_wallet->id }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- <hr>

        <a class="btn btn-info" href="{{ route('agents.index') }}">Back to All Agents</a> --}}

    </div>
</div>
@endsection


@push('footer-bottom')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/nouislider.min.js') }}"></script>
<script>
    $('#wallets').dataTable({
        sort: [],
        responsive: true
    }).show();
</script>
@endpush