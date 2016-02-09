@extends('layouts.master')
@section('title', 'Tagebuch')

@section('content')
  <div class="container">

    <h2>Tagebuch</h2>
    <p>
      Dies ist das Tagebuch. Es enth&auml;lt eine &Uuml;bersicht aller geplanten und geschriebenen Eintr&auml;ge mit ihrem jeweiligen Status.
    </p>

    <p>
      <a href="/Logout" class="btn btn-warning">Logout</a>
    </p>

  </div>
@endsection
