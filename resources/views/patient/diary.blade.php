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

    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <th>Woche</th>
        <th>Schreibimpuls</th>
        <th>Status</th>
        <th>Aktionen</th>
      </thead>
      <tbody>
      @foreach($Diary['entries'] as $i => $entry)
        <tr>
          <td>{{$i}}</td>
          <td>{{$entry['problem']}}</td>
          <td>
            <code>{{$entry['entry_status']}}</code>
          </td>
          <td>
            @if($isTherapist || $isPatient && $i <= $Diary['patient_week'])
              <a href="/Assignment/{{$Diary['name']}}/{{$i}}">Ansehen</a>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

  </div>
@endsection
