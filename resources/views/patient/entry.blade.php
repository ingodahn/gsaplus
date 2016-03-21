@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
    <div class="container">
        <form data-parsley-validate role="form"
              action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">
            {{ csrf_field() }}
            <h2>Schreibaufgabe für {{ $PatientInfo['name'] }} für Woche {{ $EntryInfo['week'] }}
                ({{ $EntryInfo['status'] }})</h2>
            Ansicht: {{ $Role }}
            @if ($Role=='therapist')
                <h3>Notizen der Therapeuten</h3>
                {{ $PatientInfo['notes'] }}
                </br>
                Das Notiz-Feld ($PatientInfo['notes']) ermöglicht dem Therapeuten die Eingabe zusätzlicher
                Informationen.
                Es wird für Patienten niemals angezeigt. Für Therapeuten ist es immer editierbar.
            @endif
            <h3>Aufgabe:</h3>
            {{ $EntryInfo['problem'] }}
            </br>
            Die Fragestellung (Problem, $EntryInfo['problem']) wird immer angezeigt.Für Patienten ist sie nicht
            editierbar.
            </br>
            Für Therapeuten ist die Fragestellung nur editierbar wenn
            die Aufgabe die aktuelle Aufgabe ist ($EntryInfo['week'] == $PatientInfo['patientWeek']) und sie vom System
            noch nicht abgeschickt wurde ($EntryInfo['status'] < 'E020').

            <h3>Antwort:</h3>
            {{ $EntryInfo['answer'] }}
            </br>
            Answer of patient on problem. This can be for week == 1: array of situations for week > 1: string
            </br>
            Für Patienten ist der zuletzt gespeicherte, automatisch gespeicherte oder abgeschickte
            Inhalt (content, $EntryInfo['answer']) immer sichtbar aber nur editierbar wenn er nicht abgeschickt oder
            überfällig ist ($EntryInfo['status'] < 'E040').
            </br>
            Für Therapeuten ist der Inhalt nur sichtbar wenn er
            abgeschickt wurde ($EntryInfo['status'] >= 'E040'). Er ist für Therapeuten niemals editierbar.


            <h3>Fragen zum Befinden:</h3>
            {{ $EntryInfo['survey'] }}
            </br>
            Für den Patienten werden die Befindensfragen (survey, $EntryInfo['survey']) nur angezeigt,
            wenn der Eintrag weder überfällig noch abgeschickt ist ($EntryInfo['status'] < 'E040').
            Sie sind dann editierbar, d.h. sie können beantwortet werden.
            </br>
            Für Therapeuten werden die Befindensfragen (survey) mit Antworten immer angezeigt. Sie sind nicht
            editierbar.

            <h3>Kommentar des Therapeuten:</h3>
            {{ $EntryInfo['comment'] }}
            </br>
            Für Patienten ist der Kommentar (comment, EntryInfo->comment()) nur sichtbar wenn er
            vom Therapeuten abgeschickt wurde ($EntyInfo['status']>= 'E050'). Er ist für Patienten niemals editierbar.
            </br>
            Für Therapeuten ist der Kommentar immer sichtbar, ggf. in einer zwischengespeicherten Version.
            </br>
            Für Therapeuten ist der Kommentar nur editierbar wenn der Eintrag vom Patienten abgeschickt
            aber der Kommentar vom Therapeuten noch nicht abgeschickt ist. ($EntryInfo['status'] == 'E040')

           @if ($Role == 'patient')
                <h3>Bewertung des Therapeutenkommentars</h3>
                {{ $EntryInfo['comment_reply'] }}
                </br>
                Der Patient kann über die Kommentar-Rückmeldung (comment_reply, $EntryInfo['comment_reply']) einmalig
                das
                Niveau
                seiner Zufriedenheit mit dem Kommentar eingeben. Das Feld wird für den Patienten immer dann angezeigt,
                wenn die Aufgabe kommentiert wurde. In diesem Fall kann der Patient die Rückmeldung eingeben und
                abschicken.
                ($EntryInfo['status'] == 'E050') Ansonsten wird das Feld nicht angezeigt.
                Wird das Feld angezeigt, so soll der Patient nachdrücklich aufgefordert werden es auszufüllen.
                </br>
                Der Therapeut sieht die Kommentar-Rückmeldung niemals.
           @endif
            <p>
                <button type="submit" class="btn" name="entryButton" value="saveDirty">Zwischenspeichern</button>
                <button type="submit" class="btn btn-warning" name="entryButton" value="save">Abschicken</button>
            </p>
        </form>
        <p>
            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}"
               class="btn btn-warning">Älter</a>
            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}"
               class="btn btn-warning">Neuer</a>
            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn btn-warning">Zur
                aktuellen Aufgabe</a>
        </p>

        <p>
            <a href="/Home" class="btn btn-warning">Zur Übersicht</a> <a href="/Logout"
                                                                         class="btn btn-warning">Logout</a>
        </p>

    </div>
@endsection
