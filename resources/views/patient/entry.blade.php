@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
  <div class="container">


    <form data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">
      {{ csrf_field() }}

      <h2>Woche {{$EntryInfo['week']}} <small>({{ $EntryInfo['status'] }}, <code>{{$Role}}</code>)</small></h2>

      @if ($Role=='therapist')
        <h3>Notizen der Therapeuten</h3>
        {{ $PatientInfo['notesOfTherapist'] }}
        </br>
      @endif

      <h3>Fragestellung</h3>
      @if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
        <p>{{$EntryInfo['problem']}}</p>
      @elseif($isTherapist && $EntryInfo['status'] < 'E020')
        <p>
        <div class="form-group">
          <label for="problem">Problem bearbeiten</label>
          <textarea class="form-control" name="problem">{{$EntryInfo['problem']}}</textarea>
        </div>
      </p>
      @endif

      <?php
        $visible = $isPatient || $isTherapist &&  $isTherapist && $EntryInfo['status'] >= 'E040';
        $editable = $isPatient && $EntryInfo['status'] < 'E040';
      ?>
      @if($visible)
        <h3>Antwort</h3>
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

                <div class="form-group">
                  <label for="situation0_description">Beschreiben Sie die Situation</label>
                  @if($editable)
                    <input type="text" class="form-control" name="situation0_description" value="{{$EntryInfo['answer'][0]['description']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation0_expectations" name="situation0_expectations" value="{{$EntryInfo['answer'][0]['expectation']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation0_their_reaction" name="situation0_their_reaction" value="{{$EntryInfo['answer'][0]['their_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation0_my_reaction" name="situation0_my_reaction" value="{{$EntryInfo['answer'][0]['my_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['my_reaction']}}
                    </p>
                  @endif
                </div>

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

                <div class="form-group">
                  <label for="situation1_description">Beschreiben Sie die Situation</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation1_description" name="situation1_description" value="{{$EntryInfo['answer'][1]['description']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation1_expectations" name="situation1_expectations" value="{{$EntryInfo['answer'][1]['expectation']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation1_their_reaction" name="situation1_their_reaction" value="{{$EntryInfo['answer'][1]['their_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation1_my_reaction" name="situation1_my_reaction" value="{{$EntryInfo['answer'][1]['my_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['my_reaction']}}
                    </p>
                  @endif
                </div>

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

                <div class="form-group">
                  <label for="situation2_description">Beschreiben Sie die Situation</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation2_description" name="situation2_description" value="{{$EntryInfo['answer'][2]['description']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation2_expectations" name="situation2_expectations" value="{{$EntryInfo['answer'][2]['expectation']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation2_their_reaction" name="situation2_their_reaction" value="{{$EntryInfo['answer'][2]['their_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <input type="text" class="form-control" id="situation2_my_reaction" name="situation2_my_reaction" value="{{$EntryInfo['answer'][2]['my_reaction']}}">
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['my_reaction']}}
                    </p>
                  @endif
                </div>

              </div>
            </div>
          </div>
        </div>
      @endif

      <hr>


      {{--
        Für den Patienten werden die Befindensfragen (survey, $EntryInfo['survey']) nur angezeigt, wenn der Eintrag weder überfällig noch abgeschickt ist ($EntryInfo['status'] < 'E040'). Sie sind dann editierbar, d.h. sie können beantwortet werden.
        Für Therapeuten werden die Befindensfragen (survey) mit Antworten immer angezeigt. Sie sind nicht editierbar.
      --}}

      <?php
        $visible = $isPatient && $EntryInfo['status'] < 'E040' || $isTherapist;
        $editable = $isPatient && $EntryInfo['status'] < 'E040';
      ?>
      @if($visible)
        <h3>Fragen zum Befinden</h3>
        <p>
          Wie oft fühlten Sie sich im Verlauf der <strong>letzten 2 Wochen</strong> durch die folgenden Beschwerden beeinträchtigt?
        </p>
        <div class="container-fluid">
          <?php
            $labels = [
              "Wenig Interesse oder Freude an Ihren Tätigkeiten",
              "Niedergeschlagenheit, Schwermut oder Hoffnungslosigkeit",
              "Nervosität, Ängstlichkeit oder Anspannung",
              "Nicht in der Lage sein, Sorgen zu stoppen oder zu kontrollieren"
            ];
            $names = [
              "phq4_interested",
              "phq4_depressed",
              "phq4_nervous",
              "phq4_troubled"
            ];
            $values = [
              $EntryInfo['survey']['phq4']['interested'],
              $EntryInfo['survey']['phq4']['depressed'],
              $EntryInfo['survey']['phq4']['nervous'],
              $EntryInfo['survey']['phq4']['troubled']
            ];
          ?>
          @for($i = 0; $i < 4; $i++)
            <div class="form-group">
              <div class="row">
                <div class="col-md-7">
                  <label for="{{$names[$i]}}">{{$labels[$i]}}</label>
                </div>
                @for($j = 0; $j < 4; $j++)
                  <div class="col-md-1">
                    <label class="radio-inline">
                      <input type="radio" name="{{$names[$i]}}" value="{{$j}}" {{$values[$i] == $j ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$j}}
                    </label>
                  </div>
                @endfor
              </div>
            </div>
          @endfor
        </div>

        <p>
          Wenn Sie Ihre beste, je erreichte Arbeitsfähigkeit mit 10 Punkten bewerten: Wie viele Punkte würden Sie dann für Ihre derzeitige Arbeitsfähigkeit geben (0 bedeutet, dass Sie derzeit arbeitsunfähig sind)?
        </p>
        <div class="container-fluid">
          <div class="form-group">
            <div class="row">
              @for($i=0; $i <= 10; $i++)
                <div class="col-md-1">
                  <label class="radio-inline">
                    <input type="radio" name="survey_wai" value="{{$i}}" {{$EntryInfo['survey']['wai'] == $i ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$i}}
                  </label>
                </div>
              @endfor
            </div>
          </div>
        </div>
      @endif

      <h3>Kommentar des Therapeuten</h3>
      <div class="form-group">
        <label for="comment">Kommentar des Therapeuten</label>
        <textarea class="form-control" id="comment" placeholder="">{{$EntryInfo['comment']}}</textarea>
        {{-- <p class="help-block">Help text here.</p> --}}
      </div>
      {{--
        Für Patienten ist der Kommentar (comment, EntryInfo->comment()) nur sichtbar wenn er vom Therapeuten abgeschickt wurde ($EntyInfo['status']>= 'E050'). Er ist für Patienten niemals editierbar.
        Für Therapeuten ist der Kommentar immer sichtbar, ggf. in einer zwischengespeicherten Version.
        Für Therapeuten ist der Kommentar nur editierbar wenn der Eintrag vom Patienten abgeschickt aber der Kommentar vom Therapeuten noch nicht abgeschickt ist. ($EntryInfo['status'] == 'E040')
      --}}

      <h3>Bewertung des Therapeutenkommentars</h3>
      @if ($Role == 'patient')
        <div class="container-fluid">

          <div class="form-group">
            <?php $checked = $EntryInfo['comment_reply']['satisfied']; ?>
            <div class="row">
              <div class="col-md-7">
                <label for="comment_reply_satisfied">Wie zufrieden waren Sie mit der Rückmeldung des Online-Therapeuten?</label>
              </div>
              @for($j = 0; $j < 4; $j++)
                <div class="col-md-1">
                  <label class="radio-inline">
                    <input type="radio" name="comment_reply_satisfied" id="comment_reply_satisfied" value="{{$j}}" {{$checked == $j ? "checked" : ""}}> {{$j}}
                  </label>
                </div>
              @endfor
            </div>
          </div>

          <div class="form-group">
            <?php $checked = $EntryInfo['comment_reply']['helpful']; ?>
            <div class="row">
              <div class="col-md-7">
                <label for="comment_reply_helpful">Wie hilfreich waren die Rückmeldungen des Online-Therapeuten?</label>
              </div>
              @for($j = 0; $j < 4; $j++)
                <div class="col-md-1">
                  <label class="radio-inline">
                    <input type="radio" name="comment_reply_helpful" id="comment_reply_helpful" value="{{$j}}" {{$checked == $j ? "checked" : ""}}> {{$j}}
                  </label>
                </div>
              @endfor
            </div>
          </div>

        </div>

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
