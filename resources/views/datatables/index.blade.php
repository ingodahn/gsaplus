@extends('layouts.test_table')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Login</th>
            <th>Registration Date</th>
            <th>Code</th>
            <th>Assignment Day</th>
            <th>Status</th>
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
                { data: 'last_login', name: 'last_login' },
                { data: 'registration_date', name: 'registration_date' },
                { data: 'code', name: 'code' },
                { data: 'assignment_day', name: 'assignment_day' },
                { data: 'patient_status', name: 'patient_status' },
            ]
        });
    });
</script>
@endpush