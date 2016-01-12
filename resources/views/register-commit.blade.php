@extends('layouts.master')
@section('title', 'Zusage')

@section('content')
  <div class="container">
    <h2>Registrierung</h2>

    <ol class="breadcrumbs">
      <li class="done">1. Begrüßung</li>
      <li class="active">2. Zusage</li>
      <li>3. Daten</li>
    </ol>

    <p>Unsere verbindliche Zusage zur Teilnahme an diesem Projekt ist Ihnen sehr wichtig:</p>

    <div class="checkbox">
      <label>
        <input type="checkbox" value="" id="commit-check-1">
        Ich bin bereit die Plattform GSA Online Plus regelmäßig zu besuchen
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="" id="commit-check-2">
        Ich bin bereit für 12 Wochen lang jede Woche ca. 45 Minuten Zeit zu investieren
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" value="" id="commit-check-3">
        Ich bin bereit regelmäßig einen kurzen Fragebogen zu beantworten
      </label>
    </div>

    <div class="pull-right">
      <a class="btn btn-accent disabled" href="/register/data">Weiter</a>
    </div>
  </div>
@endsection
