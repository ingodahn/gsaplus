@extends('layouts.master')
@section('title', 'Tagebuch')

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

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      @foreach($Diary['entries'] as $i => $entry)
        <?php
          $revealed = $isPatient && $i == $Diary['patient_week'] ? "in" : "";
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

        <div class="panel panel-{{$displayState}}">
          <div class="panel-heading" role="tab" id="heading{{$i}}">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}" aria-controls="collapse{{$i}}">

                Woche {{$i}} -- {{$entry['entry_status']}} (<code>{{$entry['entry_status_code']}}</code>)

              </a>
            </h4>
          </div>
          <div id="collapse{{$i}}" class="panel-collapse collapse {{$revealed}}" role="tabpanel" aria-labelledby="heading{{$i}}">
            <div class="panel-body">

              <p>
                {{$entry['problem']}}
              </p>

            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
