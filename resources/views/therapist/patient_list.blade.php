@extends('layouts.master')
@section('title', 'Patientenliste')
@section('additional-head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.0.2/css/responsive.bootstrap.min.css" />

  <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>

  <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>

  <script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.0.2/js/responsive.bootstrap.min.js"></script>

  <script>
    $(function() {
      $('#users-table').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/German.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
          { data: 'name', name: 'name' },
          { data: 'email', name: 'email' },
          { data: 'code', name: 'code' },
          { data: 'assignment_day', name: 'assignment_day' },
          { data: 'overdue', name: 'overdue', orderable: false, searchable: false },
        ]
      });
    });
  </script>
@endsection

@section('content')
  <div class="container">

    <h2>Slots</h2>
    <p>
      <form class="" action="/SetSlots" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Mo</span>
              <input name="Mo_slots"  type="number" class="form-control" value="{{$Slots['Montag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Di</span>
              <input name="Di_slots"  type="number" class="form-control" value="{{$Slots['Dienstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Mi</span>
              <input name="Mi_slots"  type="number" class="form-control" value="{{$Slots['Mittwoch']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Do</span>
              <input name="Do_slots"  type="number" class="form-control" value="{{$Slots['Donnerstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">So</span>
              <input name="So_slots" type="number" class="form-control" value="{{$Slots['Sonntag']}}">
            </div>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Setzen</button>
          </div>
          <div class="col-md-1">
            <a href="/Logout" class="btn btn-warning pull-right">Logout</a>
          </div>
        </div>
      </form>
    </p>

    <hr/>
    <h2>Patientenliste</h2>

    <div class="container" style="padding-top: 20px;">
      <table class="table table-bordered" id="users-table">
        <thead>
        <tr>
          <th>Name</th>
          <th>E-Mail</th>
          <th>Code</th>
          <th>Tagebuchtag</th>
          <th>Überfällig</th>
        </tr>
        </thead>
      </table>
    </div>

  </div>
@endsection
