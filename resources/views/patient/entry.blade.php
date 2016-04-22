@extends('layouts.master')
@section('title', 'Schreibaufgabe')

@section('content')
  <div class="container">


    <form data-parsley-validate role="form" action="/SaveAssignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week'] }}" method="post">
      {{ csrf_field() }}

      <h2>Woche {{$EntryInfo['week']}} <small>({{ $EntryInfo['status_text'] }}, <code>{{$Role}}</code>)</small></h2>

      @if ($Role=='therapist')
        <div class="form-group">
          <label for="notesOfTherapist">Notizen der Therapeuten</label>
          <textarea class="form-control js-auto-size" name="notesOfTherapist">{{$PatientInfo['notesOfTherapist']}}</textarea>
        </div>
      @endif

      <h3>Fragestellung</h3>
      @if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
        <p>{{$EntryInfo['problem']}}</p>
      @elseif($isTherapist && $EntryInfo['status'] < 'E020')
        <p>
        <div class="form-group">
          <label for="problem">Problem bearbeiten</label>
          <textarea class="form-control js-auto-size" name="problem">{{$EntryInfo['problem']}}</textarea>
        </div>
      </p>
      @endif

      <?php
        $visible = $isPatient || $isTherapist &&  $isTherapist && $EntryInfo['status'] >= 'E040';
        $editable = $isPatient && $EntryInfo['status'] < 'E040';
      ?>
      @if($visible)
        <h3>Tagebucheintrag</h3>
        @if ($EntryInfo['week'] == 1)
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
                    <textarea class="form-control js-auto-size" id="situation0_description"  name="situation0_description">{{$EntryInfo['answer'][0]['description']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation0_expectations" name="situation0_expectations">{{$EntryInfo['answer'][0]['expectation']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation0_their_reaction" name="situation0_their_reaction">{{$EntryInfo['answer'][0]['their_reaction']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][0]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation0_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation0_my_reaction" name="situation0_my_reaction">{{$EntryInfo['answer'][0]['my_reaction']}}</textarea>
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
                    <textarea class="form-control js-auto-size" id="situation1_description" name="situation1_description">{{$EntryInfo['answer'][1]['description']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation1_expectations" name="situation1_expectations">{{$EntryInfo['answer'][1]['expectation']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation1_their_reaction" name="situation1_their_reaction">{{$EntryInfo['answer'][1]['their_reaction']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][1]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation1_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation1_my_reaction" name="situation1_my_reaction">{{$EntryInfo['answer'][1]['my_reaction']}}</textarea>
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
                    <textarea class="form-control js-auto-size" id="situation2_description" name="situation2_description">{{$EntryInfo['answer'][2]['description']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['description']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_expectations">Wunsch ans Gegenüber</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation2_expectations" name="situation2_expectations">{{$EntryInfo['answer'][2]['expectation']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['expectation']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_their_reaction">Reaktion der anderen</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation2_their_reaction" name="situation2_their_reaction">{{$EntryInfo['answer'][2]['their_reaction']}}</textarea>
                  @else
                    <p>
                      {{$EntryInfo['answer'][2]['their_reaction']}}
                    </p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="situation2_my_reaction">Ihre Reaktion</label>
                  @if($editable)
                    <textarea class="form-control js-auto-size" id="situation2_my_reaction" name="situation2_my_reaction">{{$EntryInfo['answer'][2]['my_reaction']}}</textarea>
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
        @else
          @if ($editable)
            <textarea class="form-control js-auto-size" id="reflection"  name="reflection">{{$EntryInfo['reflection']}}</textarea>
          @else
            <p>{{ $EntryInfo['reflection'] }}</p>
          @endif
        @endif
      @endif

      <hr>

      <?php
        $visible = $isPatient && $EntryInfo['status'] < 'E040' || $isTherapist;
        $editable = $isPatient && $EntryInfo['status'] < 'E040';
      ?>
      @if($visible)
        <h3>Fragen zum Befinden</h3>
        <p>
          <strong>Wir wollen herausfinden, wie gut oder schlecht Ihre Gesundheit HEUTE ist:</strong>
        </p>
      <ul>
        <li>10 ist die beste Gesundheit, die Sie sich vorstellen können.</li>
        <li>0 ist die schlechteste Gesundheit, die Sie sich vorstellen können.</li>
        <li>Bitte wählen Sie den Wert, der Ihre Gesundheit HEUTE am besten beschreibt.</li>
      </ul>

        <div class="container-fluid big-radios">
          <div class="form-group">
            <div class="row">
              @for($i=0; $i <= 10; $i++)
                <div class="col-md-1">
                  <label class="radio-inline big-radio">
                    <input type="radio" name="health" value="{{$i}}" {{$EntryInfo['survey']['health'] == $i ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$i}}
                  </label>
                </div>
              @endfor
            </div>
          </div>
        </div>
        <br>
      <p>
        <strong>
          Derzeitige Arbeitsfähigkeit im Vergleich zu der besten, je erreichten Arbeitsfähigkeit:
        </strong>
      </p>

      <p>
        Wenn Sie Ihre beste, je erreichte Arbeitsfähigkeit mit 10 Punkten bewerten: Wie viele Punkte würden
          Sie dann für Ihre derzeitige Arbeitsfähigkeit geben? (0 bedeutet, dass Sie derzeit arbeitsunfähig sind):
        </p>
        <div class="container-fluid big-radios">
          <div class="form-group">
            <div class="row">
              @for($i=0; $i <= 10; $i++)
                <div class="col-md-1">
                  <label class="radio-inline big-radio">
                    <input type="radio" name="wai" value="{{$i}}" {{$EntryInfo['survey']['wai'] == $i ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$i}}
                  </label>
                </div>
              @endfor
            </div>
          </div>
        </div>
      @endif


      <?php
        $visible = $isPatient && $EntryInfo['status']>= 'E050' || $isTherapist;
        $editable = $isPatient && $EntryInfo['status'] == 'E040';
      ?>
      @if($visible)
        <h3>Kommentar des Therapeuten</h3>
        <div class="form-group">
          <label for="comment">Kommentar des Therapeuten</label>
          <textarea class="form-control js-auto-size" id="comment" placeholder="" {{$editable ? "" : "disabled"}}>{{$EntryInfo['comment']}}</textarea>
        </div>
      @endif

      <?php
        $visible = $isPatient && $EntryInfo['status'] == 'E050';
        $editable = $visible;
      ?>
      @if ($visible)
      <h3>Bewertung des Therapeutenkommentars</h3>

        <div class="bs-callout bs-callout-warning">
          <p>Sie werden nachdrücklich aufgefordert, diese Felder auszufüllen.</p>
        </div>

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
      @endif

      <hr>
      <p>
        @if($isPatient)
          <button type="submit" class="btn pull" name="entryButton" value="saveDirty">Zwischenspeichern</button>
        @endif
        <button type="submit" class="btn btn-primary" name="entryButton" value="save">Abschicken</button>
      </p>
      </form>


      <hr>
      <p>
        <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']-1 }}" class="btn">Älter</a>
        <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $EntryInfo['week']+1 }}" class="btn">Neuer</a>
        <a href="/Assignment/{{ $PatientInfo['name'] }}/{{ $PatientInfo['patientWeek'] }}" class="btn">Zur aktuellen Aufgabe</a>
        <a href="/Home" class="btn">Zur Übersicht</a>
      </p>


  </div>

@endsection
