@extends('layouts.test_table')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>E-mail</th>
            <th>Code</th>
            <th>Tagebuchtag</th>
            <th>Überfällig</th>
        </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.data') !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'code', name: 'code' },
                { data: 'assignment_day', name: 'assignment_day' },
                { data: 'overdue', name: 'overdue' },
            ]
        });
    });
</script>
@endpush