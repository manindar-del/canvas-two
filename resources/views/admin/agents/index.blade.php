@extends('layouts.admin')

@section('content')
<div class="_page">
    <div class="container-fluid">

        @include('partials._info')
        @include('partials._errors')

        <table class="table table-stripped" id="agents" style="display: none;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Agent Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Currency</th>
                    <th>Total</th>
                    <th>Spent</th>
                    <th>Available Balance</th>
                    <th class="table__actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agents as $_agent)
                    <tr>
                        <td>{{ $_agent->id }}</td>
                        <td>{{ $_agent->user_name }}</td>
                        <td>{{ $_agent->agent_code }}</td>
                        <td>{{ $_agent->name }}</td>
                        <td>{{ $_agent->email }}</td>
                        <td>{{ $_agent->currency }}</td>
                        <td>{{ $_agent->total_wallet_balance }}</td>
                        <td>{{ $_agent->total_spent }}</td>
                        <td>{{ $_agent->available_wallet_balance }}</td>
                        <td class="table__actions" style="width: 260px;">
                            <a href="{{ route('agents.edit', [$_agent->id]) }}" class="btn btn-sm btn-info">
                                Edit
                            </a>
                            <a href="{{ route('agents.wallets.index', [$_agent->id]) }}" class="btn btn-sm btn-info">
                                View Balances
                            </a>
                            <a href="{{ route('agents.wallets.create', [$_agent->id]) }}" class="btn btn-sm btn-info">
                                Add Balances
                            </a>
                            <a href="#" onClick="event.preventDefault(); confirm() && form{{ $_agent->id }}.submit()" class="btn btn-sm btn-danger">
                                {{-- <i class="fa fa-trash"></i>  --}}
                                Delete
                            </a>
                            <form action="{{ route('agents.destroy', [$_agent->id]) }}" method="post" id="form{{ $_agent->id }}">
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
@endsection

@push('footer-bottom')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/nouislider.min.js') }}"></script>
<script>
    jQuery('#agents').dataTable({
        sort: [],
        responsive: true
    }).show();
</script>
@endpush