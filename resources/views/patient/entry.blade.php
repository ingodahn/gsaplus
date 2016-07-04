@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('additional-head')
  <script type="text/javascript">
    function submitDirty() {
      document.getElementById("entryButton").value="saveDirty";
      $('#entry-form').parsley().destroy();
      $('#entry-form').submit();
    }

    function submitEntry() {
      document.getElementById("entryButton").value="saveEntry";
      $('#entry-form').submit();
    }

    function newAssignment() {
      document.getElementById("entryButton").value="newAssignment";
      $('#entry-form').submit();
    }
  </script>
@endsection

@section('content')
  <div class="container">


    <form id="entry-form" data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">

      <?php
        $submittable = $isPatient && in_array($EntryInfo['status'], ["E020", "E030", "E035", "E050"])
                       || $isTherapist;
      ?>

      {{ csrf_field() }}

        <input type="hidden" name="entryButton" id="entryButton" value="save"/>

      <h2>Woche {{$EntryInfo['week']}}
        @if ($isTherapist)
          <small>
            Patient: <em>{{ $PatientInfo['name'] }}</em>.
            Status: <code>{{ $EntryInfo['status'] }}</code>, {{ $EntryInfo['status_text'] }}
          </small>
        @endif
      </h2>

      {{-- @include('patient.entry.help') --}}
      @include('patient.entry.notizen')
      @include('patient.entry.impuls')
      @include('patient.entry.eintrag')
      @include('patient.entry.befinden')
      @include('patient.entry.rückmeldung')
      @include('patient.entry.bewertung')

      @if($submittable)
        <hr>
        <p>
          @if($isPatient && $EntryInfo['status'] < "E050")
            <button class="btn pull" onclick="submitDirty();">Zwischenspeichern</button>
          @endif
          <button class="btn btn-primary" onClick="submitEntry()">Abschicken</button>
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
