@extends('layouts.master')
@section('title', 'Patientenliste')
@section('additional-head')
  <link rel="stylesheet" href="/css/dataTables.bootstrap.min.css"/>
  <link rel="stylesheet" href="/css/datatables.responsive.bootstrap.min.css"/>

  <script src="/js/dataTables.min.js"></script>
  <script src="/js/dataTables.bootstrap.min.js"></script>

  <script src="/js/dataTables.responsive.min.js"></script>
  <script src="/js/dataTables.responsive.bootstrap.js"></script>

  <script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/datetime-moment.js"></script>


  <script>
    $(function() {
      $('#PatientList').DataTable({
        "language": {
          "url": "/js/dataTable-german.json"
        },
        stateSave: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
          { data: 'selection', name: 'selection', orderable: false, searchable: false},
          { data: 'name', name: 'name' },
          { data: 'code', name: 'code' },
		  { data: 'patientWeek', name: 'patientWeek' },
          { data: 'assignmentDay', name: 'assignmentDay' },
		  { data: 'status', name: 'patientStatus' },
          { data: 'statusOfNextAssignment', name: 'statusOfNextAssignment' },
          { data: 'overdue', name: 'overdue' },
		  { data: 'lastActivity', name: 'lastActivity' },
		  { data: 'therapist', name: 'therapist' }
        ],
        order: [1, 'asc'],
        createdRow: function (row, data, index) {
          $('td', row).eq(2).wrapInner('<code></code>');
        }
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
        <div class="row space-wrapped-cols">
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon day-slot"><code>Mo</code></span>
              <input name="Mo_slots"  type="number" class="form-control" value="{{$Slots['Montag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon day-slot"><code>Di</code></span>
              <input name="Di_slots"  type="number" class="form-control" value="{{$Slots['Dienstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon day-slot"><code>Mi</code></span>
              <input name="Mi_slots"  type="number" class="form-control" value="{{$Slots['Mittwoch']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon day-slot"><code>Do</code></span>
              <input name="Do_slots"  type="number" class="form-control" value="{{$Slots['Donnerstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon day-slot"><code>So</code></span>
              <input name="So_slots" type="number" class="form-control" value="{{$Slots['Sonntag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Setzen</button>
          </div>
        </div>
      </form>
    </p>
  </div>

  <div class="container-fluid">
    <hr/>
    <h2>Patientenliste  <a href="/" class="btn btn-default pull-right">Ansicht zurücksetzen</a></h2>
    <form action="/MassAction/mail" method="post">
      {{ csrf_field() }}
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
      <button type="submit" class="btn btn-primary pull-right">Mail an ausgew&auml;hlte Patienten</button>
    </form>

  </div>
@endsection
