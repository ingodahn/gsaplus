@extends('layouts.master')
@section('title', 'Zusage')
@section('additional-head')
  <script src="/js/commit-enable.js" charset="utf-8"></script>
@endsection

@section('content')
  <div class="container">
    <h2>Registrierung</h2>

    <ol class="breadcrumbs">
      <li class="done">1. Begrüßung</li>
      <li class="active">2. Zusage</li>
      <li>3. Daten</li>
    </ol>

    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;">
      </div>
    </div>

    <p>Ihre verbindliche Zusage zur Teilnahme an unserem Projekt ist uns sehr wichtig:</p>

    <div class="checkbox accept">
      <label>
        <input type="checkbox" value="" class="commit-checkbox">
        Ich bin bereit, die Plattform GSA-online plus regelmäßig zu besuchen
      </label>
    </div>
    <div class="checkbox accept">
      <label>
        <input type="checkbox" value="" class="commit-checkbox">
        Ich bin bereit, für einen Zeitraum von 12 Wochen einmal wöchentlich ca. 45 Minuten Zeit zu investieren
      </label>
    </div>
    <div class="checkbox accept">
      <label>
        <input type="checkbox" value="" class="commit-checkbox">
        Ich bin bereit, regelmäßig einen kurzen Fragebogen zu beantworten
      </label>
    </div>

    <div class="bs-callout bs-callout-info">
      <p>Bitte bestätigen Sie alle drei Aussagen und klicken Sie anschließend auf "Weiter".</p>
    </div>

    <div class="pull-right">
      <a class="btn btn-accent disabled" id="commit-next" href="/Accepted"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Weiter</a>
      <!-- TODO: better URLs - an url should describe the contents
        <a ... href="/registration/form">Weiter</a>
      -->
    </div>
  </div>
@endsection
