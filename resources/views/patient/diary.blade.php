@extends('layouts.master')
@section('title', 'Tagebuch')

@section('content')
  <div class="container">

    <h2>Tagebuch</h2>
    <p>
      Dies ist das Tagebuch von <em>{{ $name }}</em>. Es enthält eine Übersicht aller geplanten und geschriebenen Einträge mit ihrem jeweiligen Status.
    </p>
	
	<p>
      <a href="/Profile/{{ $name }}" class="btn btn-warning">Profil</a>
    </p>

    <p>
      <a href="/Logout" class="btn btn-warning">Logout</a>
    </p>

  </div>
@endsection
