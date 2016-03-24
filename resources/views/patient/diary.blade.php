@extends('layouts.master')
@section('title', 'Tagebuch')

@section('content')
  <div class="container">

    <h2>Tagebuch</h2>
    <p>
      Dies ist das Tagebuch von <em>{{ $Diary['name'] }}</em>. Es enthält eine Übersicht aller geplanten und geschriebenen Einträge mit ihrem jeweiligen Status.
    </p>
	<p>Es ist Woche {{ $Diary['patient_week'] }}/12</p>
	<p>
      <a href="/Profile/{{ $Diary['name'] }}" class="btn btn-warning">Profil</a>
    </p>

    <p>
      <ul>
        @for ($i=1; $i <= 12; $i++)
        <li> Woche {{ $i }}: {{ $Diary['entries'][$i]['problem'] }} ({{ $Diary['entries'][$i]['entry_status'] }})
          <a href="/Assignment/{{ $Diary['name'] }}/{{ $i }}" class="btn btn-warning">Ansehen</a>
        </li>
        @endfor
      </ul>
    </p>

  </div>
@endsection
