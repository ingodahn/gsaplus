@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
  <div class="container">


    <form data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">
      {{ csrf_field() }}

      <h2>Woche {{$EntryInfo['week']}} <small>({{ $EntryInfo['status'] }})</small></h2>
      <p>
        Ansicht: <code>{{$Role}}</code>
      </p>

      @if ($Role=='therapist')
        <h3>Notizen der Therapeuten</h3>
        {{ $PatientInfo['notes'] }}
        </br>
        Das Notiz-Feld ($PatientInfo['notes']) ermöglicht dem Therapeuten die Eingabe zusätzlicher Informationen. Es wird für Patienten niemals angezeigt. Für Therapeuten ist es immer editierbar.
      @endif

      <!-- Problem: Die Fragestellung (Problem, $EntryInfo['problem']) wird immer angezeigt. Für Patienten ist sie nicht editierbar. Für Therapeuten ist die Fragestellung nur editierbar wenn die Aufgabe die aktuelle Aufgabe ist ($EntryInfo['week'] == $PatientInfo['patientWeek']) und sie vom System noch nicht abgeschickt wurde ($EntryInfo['status'] < 'E020'). -->

      <p>{{ $EntryInfo['problem'] }}</p>

      <!-- Antwort Answer of patient on problem. This can be for week == 1: array of situations for week > 1: string Für Patienten ist der zuletzt gespeicherte, automatisch gespeicherte oder abgeschickte Inhalt (content, $EntryInfo['answer']) immer sichtbar aber nur editierbar wenn er nicht abgeschickt oder überfällig ist ($EntryInfo['status'] < 'E040'). Für Therapeuten ist der Inhalt nur sichtbar wenn er abgeschickt wurde ($EntryInfo['status'] >= 'E040'). Er ist für Therapeuten niemals editierbar. -->

      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Situation 1
              </a>
            </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              <h5>Beschreiben Sie die Situation</h5>
              {{ $EntryInfo['answer'][0]['description'] }}
              <h5>Wunsch ans Gegenüber:</h5>
              {{ $EntryInfo['answer'][0]['expectation'] }}
              <h5>Reaktion der anderen:</h5>
              {{ $EntryInfo['answer'][0]['their_reaction'] }}
              <h5>Ihre Reaktion:</h5>
              {{ $EntryInfo['answer'][0]['my_reaction'] }}
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title">
              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Situation 2
              </a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
              <h5>Beschreiben Sie die Situation</h5>
              {{ $EntryInfo['answer'][1]['description'] }}
              <h5>Wunsch ans Gegenüber:</h5>
              {{ $EntryInfo['answer'][1]['expectation'] }}
              <h5>Reaktion der anderen:</h5>
              {{ $EntryInfo['answer'][1]['their_reaction'] }}
              <h5>Ihre Reaktion:</h5>
              {{ $EntryInfo['answer'][1]['my_reaction'] }}
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingThree">
            <h4 class="panel-title">
              <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Situation 3
              </a>
            </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
            <div class="panel-body">
              <h5>Beschreiben Sie die Situation</h5>
              {{ $EntryInfo['answer'][2]['description'] }}
              <h5>Wunsch ans Gegenüber:</h5>
              {{ $EntryInfo['answer'][2]['expectation'] }}
              <h5>Reaktion der anderen:</h5>
              {{ $EntryInfo['answer'][2]['their_reaction'] }}
              <h5>Ihre Reaktion:</h5>
              {{ $EntryInfo['answer'][2]['my_reaction'] }}
            </div>
          </div>
        </div>
      </div>


      <h3>Fragen zum Befinden</h3>
      <p>{{$EntryInfo['survey']}}</p>
      {{--
        Für den Patienten werden die Befindensfragen (survey, $EntryInfo['survey']) nur angezeigt, wenn der Eintrag weder überfällig noch abgeschickt ist ($EntryInfo['status'] < 'E040'). Sie sind dann editierbar, d.h. sie können beantwortet werden.
        Für Therapeuten werden die Befindensfragen (survey) mit Antworten immer angezeigt. Sie sind nicht editierbar.
      --}}

      <h3>Kommentar des Therapeuten</h3>
      <p>{{$EntryInfo['comment']}}</p>
      {{--
        Für Patienten ist der Kommentar (comment, EntryInfo->comment()) nur sichtbar wenn er vom Therapeuten abgeschickt wurde ($EntyInfo['status']>= 'E050'). Er ist für Patienten niemals editierbar.
        Für Therapeuten ist der Kommentar immer sichtbar, ggf. in einer zwischengespeicherten Version.
        Für Therapeuten ist der Kommentar nur editierbar wenn der Eintrag vom Patienten abgeschickt aber der Kommentar vom Therapeuten noch nicht abgeschickt ist. ($EntryInfo['status'] == 'E040')
      --}}

      @if ($Role == 'patient')
        <h3>Bewertung des Therapeutenkommentars</h3>
        <p>{{$EntryInfo['comment_reply']}}</p>
        {{--
          Der Patient kann über die Kommentar-Rückmeldung (comment_reply, $EntryInfo['comment_reply']) einmalig das Niveau seiner Zufriedenheit mit dem Kommentar eingeben. Das Feld wird für den Patienten immer dann angezeigt, wenn die Aufgabe kommentiert wurde. In diesem Fall kann der Patient die Rückmeldung eingeben und abschicken. ($EntryInfo['status'] == 'E050') Ansonsten wird das Feld nicht angezeigt. Wird das Feld angezeigt, so soll der Patient nachdrücklich aufgefordert werden es auszufüllen.
          Der Therapeut sieht die Kommentar-Rückmeldung niemals.
        --}}
      @endif

      <p>
        <button type="submit" class="btn" name="entryButton" value="saveDirty">Zwischenspeichern</button>
        <button type="submit" class="btn btn-warning" name="entryButton" value="save">Abschicken</button>
      </p>
    </form>


    <p>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}" class="btn btn-warning">Älter</a>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']+1 }}" class="btn btn-warning">Neuer</a>
      <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn btn-warning">Zur aktuellen Aufgabe</a>
    </p>
    <p>
      <a href="/Home" class="btn btn-warning">Zur Übersicht</a>
    </p>


  </div>
@endsection
