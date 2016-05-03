@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
  <div class="container">


    <form data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">

      <?php
        $submittable = $isPatient && in_array($EntryInfo['status'], ["E020", "E030", "E050"])
                    || $isTherapist && in_array($EntryInfo['status'], ["E010", "E040"]);
      ?>

      {{ csrf_field() }}

      <h2>Woche {{$EntryInfo['week']}} <small>({{ $EntryInfo['status_text'] }}, <code>{{$EntryInfo['status']}}</code>)</small></h2>

      @include('patient.entry.help')
      @include('patient.entry.notizen')
      @include('patient.entry.impuls')
      @include('patient.entry.eintrag')
      <hr>
      @include('patient.entry.befinden')
      @include('patient.entry.rückmeldung')
      @include('patient.entry.bewertung')

      @if($submittable)
        <hr>
        <p>
          @if($isPatient)
            <button type="submit" class="btn pull" name="entryButton" value="saveDirty">Zwischenspeichern</button>
          @endif
          <button type="submit" class="btn btn-primary" name="entryButton" value="save">Abschicken</button>
        </p>
      @else
        <p>
          <a href="/Home" class="btn btn-primary">Zur Übersicht</a>
        </p>
      @endif

    </form>


    <hr>
    <p>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}" class="btn btn-default">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Älter
      </a>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn btn-default">Zum aktuellen Schreibimpuls</a>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']+1 }}" class="btn btn-default">
        Neuer
        <i class="fa fa-chevron-right" aria-hidden="true"></i>
      </a>
      <a href="/Home" class="btn btn-default pull-right">Zur Übersicht</a>
    </p>


  </div>

@endsection
