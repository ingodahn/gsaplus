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
      <a href="javascript:void(0)" data-toggle="popover" data-placement="right" data-html="true" data-trigger="focus" title="Ihr Tagebuch" data-content="Hier kommt eine Schnellhilfe zur Bedienung des Tagebuchs.">
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

      <p>
        Wenn Sie noch keinen Schreibimpuls erhalten haben, warten Sie bitte ab, bis Ihr Online Therapeut Ihnen einen Schreibimpuls gibt.
      </p>
    @endif

    <p>Es ist Woche <strong>{{$Diary['patient_week']}}</strong> von 12.</p>

    <div class="bs-callout bs-callout-info">
      <p>{{$Diary['next_assignment']}}</p>
    </div>

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
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}" aria-controls="collapse{{$i}}">

                <small><i class="fa fa-chevron-{{$revealed ? "down" : "right"}} accordion-indicator" aria-hidden="true"></i></small> <strong>Woche {{$i}}</strong> - {{$entry['entry_status']}}
              </a>
              <a href="#" class="pull-right">Zum Eintrag <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
            </h4>
          </div>
          <div id="collapse{{$i}}" class="panel-collapse collapse {{$revealed ? "in" : ""}}" role="tabpanel" aria-labelledby="heading{{$i}}">
            <div class="panel-body">

              @if($current)
                <p><em>Dies ist die aktuelle Aufgabe.</em></p>
              @endif
              <p>Status-Code: <code>{{$entry['entry_status_code']}}</code></p>
              <p>{{$entry['problem']}}</p>

            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
