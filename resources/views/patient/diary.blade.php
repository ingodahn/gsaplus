@extends('layouts.master')
@section('title', 'Tagebuch')

@section('additional-head')
  <script type="text/javascript">
    $(document).ready(function(){
      function extractIndex(id) {
        return /collapse(\d+)/.exec(id)[1];
      }

      function showClosedIcon(element) {
        $("#heading" + extractIndex(element.id) + " .accordion-indicator")
            .removeClass("fa-chevron-down")
            .addClass("fa-chevron-right");
      }

      function showOpenIcon(element) {
        $("#heading" + extractIndex(element.id) + " .accordion-indicator")
            .removeClass("fa-chevron-right")
            .addClass("fa-chevron-down");
      }

      $('.panel-collapse').on('show.bs.collapse', function () {
        showOpenIcon(this);
      });

      $('.panel-collapse').on('hide.bs.collapse', function () {
        showClosedIcon(this);
      });
    });
  </script>
@endsection

@section('content')
  <div class="container">

    @if($isTherapist)
      <h2>Profil</h2>
      <p>
        <a class="btn btn-primary" href="/Profile/{{$Diary['name']}}">Profil von {{$Diary['name']}} aufrufen</a>
      </p>
    @endif

    <h2>Tagebuch
      <a href="javascript:void(0)" data-toggle="popover" data-placement="right" data-html="true" data-trigger="focus" title="Ihr Tagebuch" data-content="
      Das Tagebuch ist in 12 Wochen untergliedert und ermöglicht Ihnen, auf den ersten Blick zu erkennen, in welcher Woche der Online-Nachsorge Sie sich aktuell befinden. Sobald Sie oder Ihr Online-Therapeut eine Aktion durchführen, wird dies entsprechend in der Übersicht vermerkt. Zusätzlich zeigen Ihnen die Farben an, ob Sie die Aufgaben der Woche abgeschlossen haben (grün), noch etwas zu bearbeiten ist (blau) oder die Bearbeitungszeit bereits abgelaufen ist (grau).So sind Sie schnell und übersichtlich über den aktuellen Stand Ihrer Online-Nachsorge informiert.<br><br>
      <strong>Wochenübersicht und Farbsystem – Tagebuch</strong><br><br>
      Blau
      <ul>
        <li>Schreibimpuls liegt für Sie bereit</li>
        <li>Tagebucheintrag in Bearbeitung und zwischengespeichert</li>
        <li>Tagebucheintrag abgeschickt</li>
        <li>Ihr Online-Therapeut hat geantwortet</li>
        <li>Bitte bewerten Sie die Rückmeldung Ihres Online-Therapeuten</li>
      </ul>
      Grün
      <ul>
        <li>Tagebucheintrag und Rückmeldung abgeschlossen</li>
      </ul>
      Grau
      <ul>
        <li>Kein Tagebucheintrag vorhanden</li>
      </ul>
      ">
        <i class="fa fa-question-circle"></i>
      </a>
    </h2>

    @if($isTherapist)
      <p>
        Dies ist das Tagebuch von <em>{{ $Diary['name'] }}</em>. Es enthält eine Übersicht aller geplanten und geschriebenen Einträge mit ihrem jeweiligen Status.
      </p>
    @endif

    @if($isPatient)
      <p>
        Dies ist Ihr Tagebuch. Es enthält eine Übersicht aller geplanten und geschriebenen Einträge mit ihrem jeweiligen Status.
      </p>
    @endif

    <p>Es ist Woche <strong>{{$Diary['patient_week']}}</strong> von 12.</p>
    @if ($Diary['next_assignment'] !== "")
    <div class="bs-callout bs-callout-info">
      <p>{{$Diary['next_assignment']}}</p>
    </div>
      @endif

    <label>Klicken Sie auf die Wochen, um diese auszuklappen<br>
      Zum Eintrag gelangen Sie über den Link auf der rechten Seite der Zeile</label>
    <div class="panel-group diary-accordion" id="accordion" role="tablist" aria-multiselectable="true">
      @foreach($Diary['entries'] as $i => $entry)
        <?php
          $current = $i == $Diary['patient_week'];
          $revealed = $isPatient && $current;
          $class = $current ? "diary-panel-current" : "diary-panel";
          switch($entry['entry_status_code']) {
            case "E020": $displayState = "primary"; break;
            case "E030": $displayState = "primary"; break;
            case "E040": $displayState = "primary"; break;
            case "E050": $displayState = "primary"; break;
            case "E060": $displayState = "success"; break;
            case "E070": $displayState = "warning"; break;
            default: $displayState = "default"; break;
          }
        ?>

        <div class="panel panel-{{$displayState}} {{$class}}" id="accordion-diary">
          <div class="panel-heading" role="tab" id="heading{{$i}}">
            <h4 class="panel-title">
              @if($isPatient && $i <= $Diary['patient_week'] || $isTherapist)
                <div class="row">
                  <div class="col-xs-10 col-sm-9">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}" aria-controls="collapse{{$i}}">
                      <small><i class="fa fa-chevron-{{$revealed ? "down" : "right"}} accordion-indicator" aria-hidden="true"></i></small> <strong>Woche {{$i}}</strong> - {{$entry['entry_status']}}
                    </a>
                  </div>
                  <div class="col-xs-2 col-md-3">
                    <a href="/Assignment/{{$Diary['name']}}/{{$i}}" class="pull-right">Zum Eintrag <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                  </div>
                </div>
              @else
                <i class="fa fa-ban" aria-hidden="true"></i> <strong>Woche {{$i}}</strong> - noch nicht verfügbar
              @endif
            </h4>
          </div>
          <div id="collapse{{$i}}" class="panel-collapse collapse {{$revealed ? "in" : ""}}" role="tabpanel" aria-labelledby="heading{{$i}}">
            <div class="panel-body">

              @if($current)
                <p><em>Das ist der aktuelle Schreibimpuls.</em></p>
              @endif
              <p>{{$entry['problem']}}</p>

            </div>
          </div>
        </div>
      @endforeach
    </div>
      @if ($Diary['patient_week']>1)
      <p>
        <a class="btn btn-primary" href="/CommentedDiary/{{$Diary['name']}}"><i class="fa fa-book" aria-hidden="true"></i> Wochenrückblick</a>
      </p>
        @endif
  </div>
@endsection
