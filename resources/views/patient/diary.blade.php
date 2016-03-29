@extends('layouts.master')
@section('title', 'Tagebuch')

@section('content')
  <div class="container">

    <h2>Tagebuch</h2>
    <p>
      Dies ist das Tagebuch von <em>{{ $Diary['name'] }}</em>. Es enthält eine Übersicht aller geplanten und geschriebenen Einträge mit ihrem jeweiligen Status.
    </p>

    <p>Es ist Woche <strong>{{$Diary['patient_week']}}</strong> von 12.</p>

    <p>
      <a href="/Profile/{{$Diary['name']}}">Profil von {{$Diary['name']}}.</a>
    </p>

    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <th>Woche</th>
        <th>Aufgabe</th>
        <th>Status</th>
        <th>Aktionen</th>
      </thead>
      <tbody>
      @for ($i=1; $i <= 12; $i++)
        <tr>
          <td>{{$i}}</td>
          <td>{{$Diary['entries'][$i]['problem']}}</td>
          <td>
            <code>{{$Diary['entries'][$i]['entry_status']}}</code>
          </td>
          <td>
            <a href="/Assignment/{{$Diary['name']}}/{{$i}}">Ansehen</a>
          </td>
        </tr>
      @endfor
      </tbody>
    </table>

  </div>
@endsection
