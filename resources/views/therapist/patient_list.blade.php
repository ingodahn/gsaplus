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

  <script type="text/javascript">
    $(function(){
      $("#reset").on("click", function() {
        localStorage.removeItem("DataTables_PatientList_/patient_list");
      });
    });
  </script>
@endsection

@section('content')
  <div class="container">
    <h2>Slots
      <a href="javascript:void(0)" data-toggle="popover" class="btn" data-placement="right" data-html="true" data-trigger="focus" title="Freie Slots für die Registrierung" data-content="Die aktuelle Menge der freien Slots pro Wochentag kann hier überprüft und durch Eintragen von Zahlenwerten verändert werden.<br><strong>Achtung:</strong> Wenn alle Slots auf '0' stehen, wird die Anmeldung automatisch gesperrt und Patienten erhalten auf der Startseite statt der Codeeingabe eine entsprechende Meldung und einen Link zum Kontaktformular.">
        <i class="fa fa-question-circle"></i>
      </a>
    </h2>
    <p>
      <form class="" action="/SetSlots" method="post">
        {{ csrf_field() }}

        <?php
          function echoIndicatorClass($openSlots) {
            if ($openSlots <= 5) {
              if ($openSlots == 0) {
                echo("slots-none");
              } else {
                echo("slots-few");
              }
            }
          }
        ?>

        <div class="row space-wrapped-cols">
          <div class="col-md-2">
            <div class="input-group {{ echoIndicatorClass($Slots['Montag']) }}">
              <span class="input-group-addon day-slot"><code>Mo</code></span>
              <input name="Mo_slots" min="0" type="number" class="form-control" value="{{$Slots['Montag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group {{ echoIndicatorClass($Slots['Dienstag']) }}">
              <span class="input-group-addon day-slot"><code>Di</code></span>
              <input name="Di_slots" min="0" type="number" class="form-control" value="{{$Slots['Dienstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group {{ echoIndicatorClass($Slots['Mittwoch']) }}">
              <span class="input-group-addon day-slot"><code>Mi</code></span>
              <input name="Mi_slots" min="0" type="number" class="form-control" value="{{$Slots['Mittwoch']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group {{ echoIndicatorClass($Slots['Donnerstag']) }}">
              <span class="input-group-addon day-slot"><code>Do</code></span>
              <input name="Do_slots" min="0" type="number" class="form-control" value="{{$Slots['Donnerstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group {{ echoIndicatorClass($Slots['Sonntag']) }}">
              <span class="input-group-addon day-slot"><code>So</code></span>
              <input name="So_slots" min="0" type="number" class="form-control" value="{{$Slots['Sonntag']}}">
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
    <h2>Patientenliste
      <a href="javascript:void(0)" data-toggle="popover" class="btn" data-placement="right" data-html="true" data-trigger="focus" title="Alle im System registrierten Patienten mit Sortier- und Filterfunktionen" data-content="
      Es gibt folgende <strong>Patienten-Status (P)</strong>
      <ul>
        <li>Registriert (P020)</li>
        <li>Entlassungsdatum erfasst (P025)</li>
        <li>Entlassen (P028)</li>
        <li>Schreibimpuls erhalten (P030)</li>
        <li>Tagebucheintrag in Bearbeitung und zwischengespeichert (P040)</li>
        <li>Tagebucheintrag in Kürze fällig (P045)</li>
        <li>Tagebucheintrag abgeschickt (P050)</li>
        <li>Ihr Online-Therapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung. (P060)</li>
        <li>Tagebucheintrag und Rückmeldung abgeschlossen (P065)</li>
        <li>Aufgabe verpasst (P070)</li>
        <li>Mitarbeit beendet (P130)</li>
        <li>Interventionszeit beendet (P140)</li>
      </ul>
      Die Zahlen dienen zur Sortierung und sind so gewählt, dass sie das Verfahren chronologisch abbilden.<br>
      <br>
      Es gibt auch folgende <strong>Schreibimpuls-Status (E)</strong>
      <ul>
        <li>Schreibimpuls nicht definiert (E010)</li>
        <li>Schreibimpuls definiert (E015)</li>
        <li>Schreibimpuls liegt für Sie bereit (E020)</li>
        <li>Tagebucheintrag in Bearbeitung und zwischengespeichert (E030)</li>
        <li>Tagebucheintrag abgeschickt (E040)</li>
        <li>Ihr Online-Therapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung. (E050)</li>
        <li>Tagebucheintrag und Rückmeldung abgeschlossen (E060)</li>
        <li>Kein Tagebucheintrag vorhanden (E070)</li>
        <li>Kein aktueller Schreibimpuls (E100)</li>
      </ul>
      Der Wert für <strong>Überfällig</strong> wird wie folgt berechnet:<br>
      Überfällig (overdue) = Wert der Form 'Anzahl der überfälligen Einträge' / 'Aktuelle Wochennr' = Anzahl der bereits gegebenen Schreibimpulse
      ">
        <i class="fa fa-question-circle"></i>
      </a>
      <a href="/" class="btn btn-default pull-right" id="reset">Ansicht zurücksetzen</a>
    </h2>
    <form action="/MassAction/mail" method="post">
      {{ csrf_field() }}
        <table class="table table-bordered" id="PatientList">
          <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Code</th>
            <th>Woche</th>
            <th>Schreibtag</th>
            <th>Status</th>
            <th>Schreibimpuls</th>
            <th>Überfällig</th>
            <th>Zuletzt aktiv</th>
            <th>Therapeut</th>
          </tr>
          </thead>
        </table>
      <button type="submit" class="btn btn-primary">Mail an ausgewählte Patienten</button>
    </form>

  </div>
@endsection
