@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
    <div class="container">
        <form data-parsley-validate role="form"
              action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">
            {{ csrf_field() }}
            <h2>Schreibaufgabe für {{ $PatientInfo['name'] }} für Woche {{ $EntryInfo['week'] }}
                ({{ $EntryInfo['status'] }})</h2>
            
            @if ($Role=='therapist')
                <h3>Notizen der Therapeuten</h3>
                {{ $PatientInfo['notesOfTherapist'] }}
                <!--
                Das Notiz-Feld ($PatientInfo['notes']) ermöglicht dem Therapeuten die Eingabe zusätzlicher
                Informationen.
                Es wird für Patienten niemals angezeigt. Für Therapeuten ist es immer editierbar.
                -->
                @endif

                        <!-- Problem
                Die Fragestellung (Problem, $EntryInfo['problem']) wird immer angezeigt.Für Patienten ist sie nicht
                editierbar.
                </br>
                Für Therapeuten ist die Fragestellung nur editierbar wenn
                die Aufgabe die aktuelle Aufgabe ist ($EntryInfo['week'] == $PatientInfo['patientWeek']) und sie vom System
                noch nicht abgeschickt wurde ($EntryInfo['status'] < 'E020').
             -->
                <h3>Aufgabe</h3>
                {{ $EntryInfo['problem'] }}

                        <!-- Antwort
            Answer of patient on problem. This can be for week == 1: array of situations for week > 1: string
            </br>
            Für Patienten ist der zuletzt gespeicherte, automatisch gespeicherte oder abgeschickte
            Inhalt (content, $EntryInfo['answer']) immer sichtbar aber nur editierbar wenn er nicht abgeschickt oder
            überfällig ist ($EntryInfo['status'] < 'E040').
            </br>
            Für Therapeuten ist der Inhalt nur sichtbar wenn er
            abgeschickt wurde ($EntryInfo['status'] >= 'E040'). Er ist für Therapeuten niemals editierbar.
            -->
                <h3>Antwort:</h3>
                <details open>
                    <summary>
                        <h4>Erste Situation</h4>
                    </summary>
                    <h5>Beschreiben Sie die Situation</h5>
                    {{ $EntryInfo['answer'][0]['description'] }}
                    <h5>Wunsch ans Gegenüber:</h5>
                    {{ $EntryInfo['answer'][0]['expectation'] }}
                    <h5>Reaktion der anderen:</h5>
                    {{ $EntryInfo['answer'][0]['their_reaction'] }}
                    <h5>Ihre Reaktion:</h5>
                    {{ $EntryInfo['answer'][0]['my_reaction'] }}
                </details>
                <details>
                    <summary>
                        <h4>Zweite Situation</h4>
                    </summary>
                    <h5>Beschreiben Sie die Situation</h5>
                    {{ $EntryInfo['answer'][1]['description'] }}
                    <h5>Wunsch ans Gegenüber:</h5>
                    {{ $EntryInfo['answer'][1]['expectation'] }}
                    <h5>Reaktion der anderen:</h5>
                    {{ $EntryInfo['answer'][1]['their_reaction'] }}
                    <h5>Ihre Reaktion:</h5>
                    {{ $EntryInfo['answer'][1]['my_reaction'] }}
                </details>
                <details>
                    <summary>
                        <h4>Dritte Situation</h4>
                    </summary>
                    <h5>Beschreiben Sie die Situation</h5>
                    {{ $EntryInfo['answer'][2]['description'] }}
                    <h5>Wunsch ans Gegenüber:</h5>
                    {{ $EntryInfo['answer'][2]['expectation'] }}
                    <h5>Reaktion der anderen:</h5>
                    {{ $EntryInfo['answer'][2]['their_reaction'] }}
                    <h5>Ihre Reaktion:</h5>
                    {{ $EntryInfo['answer'][2]['my_reaction'] }}
                </details>


                <details>
                    <summary>
                        <h3>Fragen zum Befinden:</h3>
                    </summary>
                    Wie oft fühlten Sie sich im Verlauf der <b>letzten 2 Wochen</b> durch die folgenden
                    Beschwerden beeinträchtigt? (0...3)</li>
                    <ol>
                        <li>Wenig Interesse oder Freude an Ihren
                            Tätigkeiten: {{ $EntryInfo['survey']['phq4']['interested'] }}</li>
                        <li>Niedergeschlagenheit, Schwermut oder
                            Hoffnungslosigkeit: {{ $EntryInfo['survey']['phq4']['depressed'] }} </li>
                        <li>Nervosität, Ängstlichkeit oder
                            Anspannung: {{ $EntryInfo['survey']['phq4']['nervous'] }}</li>
                        <li>Nicht in der Lage sein, Sorgen zu stoppen oder zu
                            kontrollieren: {{ $EntryInfo['survey']['phq4']['troubled'] }}</li>
                    </ol>
                    <p>
                        Wenn Sie Ihre beste, je erreichte Arbeitsfähigkeit mit 10 Punkten bewerten: Wie viele Punkte
                        würden Sie dann für Ihre derzeitige Arbeitsfähigkeit geben (0 bedeutet, dass Sie derzeit
                        arbeitsunfähig sind)?
                        <!--    O 0    O 1    O 2    O 3    O 4    O 5    O 6    O 7    O 8    O 9    O 10 -->
                        <!--   Völlig                                                                   Derzeit die beste -->
                        <!--   arbeitsunfähig                                                           Arbeitsfähigkeit -->

                        {{ $EntryInfo['survey']['wai'] }}
                    </p>


                    <!--
                    Für den Patienten werden die Befindensfragen (survey, $EntryInfo['survey']) nur angezeigt,
                    wenn der Eintrag weder überfällig noch abgeschickt ist ($EntryInfo['status'] < 'E040').
                    Sie sind dann editierbar, d.h. sie können beantwortet werden.
                    </br>
                    Für Therapeuten werden die Befindensfragen (survey) mit Antworten immer angezeigt. Sie sind nicht
                    editierbar.
                    -->
                </details>
                <details>
                    <summary>
                        <h3>Kommentar des Therapeuten</h3>
                    </summary>
                    {{ $EntryInfo['comment'] }}
                    <!--
                    Für Patienten ist der Kommentar (comment, EntryInfo->comment()) nur sichtbar wenn er
                    vom Therapeuten abgeschickt wurde ($EntyInfo['status']>= 'E050'). Er ist für Patienten niemals
                    editierbar.
                    </br>
                    Für Therapeuten ist der Kommentar immer sichtbar, ggf. in einer zwischengespeicherten Version.
                    </br>
                    Für Therapeuten ist der Kommentar nur editierbar wenn der Eintrag vom Patienten abgeschickt
                    aber der Kommentar vom Therapeuten noch nicht abgeschickt ist. ($EntryInfo['status'] == 'E040')
                    -->
                </details>
                @if ($Role == 'patient')
                    <details>
                        <summary>
                            <h3>Bewertung des Therapeutenkommentars</h3>
                        </summary>
                        <ul>
                            <li>Wie zufrieden waren Sie mit der Rückmeldung des Online-Therapeuten? (0 1 2 3 4)
                                {{ $EntryInfo['comment_reply']['satisfied'] }}</li>
                            <li>Wie hilfreich waren die Rückmeldungen des Online-Therapeuten? (0 1 2 3
                                4) {{ $EntryInfo['comment_reply']['helpful'] }}</li>
                            </li>
                        </ul>
                        <!--
                        Der Patient kann über die Kommentar-Rückmeldung (comment_reply, $EntryInfo['comment_reply'])
                        einmalig das Niveau seiner Zufriedenheit mit dem Kommentar eingeben. Das Feld wird für den Patienten immer dann
                        angezeigt, wenn die Aufgabe kommentiert wurde. In diesem Fall kann der Patient die Rückmeldung eingeben und
                        abschicken. ($EntryInfo['status'] == 'E050') Ansonsten wird das Feld nicht angezeigt.
                        Wird das Feld angezeigt, so soll der Patient nachdrücklich aufgefordert werden es auszufüllen.
                        Der Therapeut sieht die Kommentar-Rückmeldung niemals.
                        -->
                    </details>
                @endif
                                <p>
                                    <button type="submit" class="btn" name="entryButton" value="saveDirty">Zwischenspeichern</button>
                                    <button type="submit" class="btn btn-warning" name="entryButton" value="save">Abschicken</button>
                                </p>
                        </form>
                        <p>
                            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}"
               class="btn btn-warning">Älter</a>
            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']+1 }}"
               class="btn btn-warning">Neuer</a>
            <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn btn-warning">Zur
                aktuellen Aufgabe</a>
        </p>

        <p>
            <a href="/Home" class="btn btn-warning">Zur Übersicht</a> 
        </p>

    </div>
@endsection
