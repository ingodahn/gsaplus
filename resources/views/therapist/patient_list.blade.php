@extends('layouts.master')
@section('title', 'Patientenliste')
@section('additional-head')
  <link rel="stylesheet" href="/css/dataTables.bootstrap.min.css"/>
  <link rel="stylesheet" href="/css/datatables.responsive.bootstrap.min.css"/>

  <script src="/js/dataTables.min.js"></script>
  <script src="/js/dataTables.bootstrap.min.js"></script>

  <script src="/js/dataTables.responsive.min.js"></script>
  <script src="/js/dataTables.responsive.bootstrap.js"></script>

  <script>
    $(function() {
      $('#PatientList').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/German.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
          { data: 'selection', name: 'selection'},
          { data: 'name', name: 'name' },
          { data: 'code', name: 'code' },
		  { data: 'patientWeek', name: 'patientWeek', orderable: false, searchable: false },
          { data: 'assignment_day', name: 'assignment_day' },
		  { data: 'patient_status', name: 'patientStatus', orderable: false, searchable: false },
          { data: 'status_of_next_assignment', name: 'status_of_next_assignment', orderable: false, searchable: false },
          { data: 'overdue', name: 'overdue', orderable: false, searchable: false },
		  { data: 'last_activity', name: 'lastActivity', orderable: false, searchable: false },
		  { data: 'therapist', name: 'therapist', orderable: false, searchable: false }
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
    <h3>!! Bitte noch nicht suchen oder sortieren !!</h3>
    <form action="/MassAction/mail" method="post">
      {{ csrf_field() }}
      <div class="container" style="padding-top: 20px;">
        <table class="table table-bordered" id="PatientList">
          <thead>
          <tr>
            <th>Auswahl</th>
            <th>Name</th>
            <th>Code</th>
            <th>Woche</th>
            <th>Tagebuchtag</th>
            <th>Status</th>
            <th>N&auml;chste Aufgabe</th>
            <th>Überfällig</th>
            <th>Zuletzt aktiv</th>
            <th>Therapeut</th>
          </tr>
          </thead>
        </table>
      </div>
      <button type="submit" class="btn btn-primary pull-right">Mail an ausgew&auml;hlte Patienten</button>
    </form>

  </div>
@endsection
