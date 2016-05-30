@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
  <div class="container">


    <form data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">

      <?php
        $submittable = $isPatient && in_array($EntryInfo['status'], ["E020", "E030", "E035", "E050"])
                       || $isTherapist;
      ?>

      {{ csrf_field() }}

      <h2>Woche {{$EntryInfo['week']}}
        @if ($isTherapist)
          <small>
            Patient: <em>{{ $PatientInfo['name'] }}</em>.
            Status: <code>{{ $EntryInfo['status'] }}</code>, {{ $EntryInfo['status_text'] }}
          </small>
        @endif
      </h2>

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
          @if($isPatient && $EntryInfo['status'] < "E050")
            <button type="submit" class="btn pull" name="entryButton" value="saveDirty">Zwischenspeichern</button>
          @endif
          <button type="submit" class="btn btn-primary" name="entryButton" value="save">Abschicken</button>
        </p>
      @else

      @endif

    </form>


    <hr>
    <p>
      @if ($EntryInfo['week'] > 1)
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}" class="btn btn-default">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
        Älter
      </a>
      @endif
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn btn-default">Zum aktuellen Schreibimpuls</a>

      @if ($isTherapist && $EntryInfo['week'] < 12 || $EntryInfo['week'] < $PatientInfo['patientWeek'])
        <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']+1 }}" class="btn btn-default">
        Neuer
        <i class="fa fa-chevron-right" aria-hidden="true"></i>
      </a>
        @endif
      <a href="/Home" class="btn btn-default pull-right">Zur Übersicht</a>
    </p>


  </div>

@endsection
