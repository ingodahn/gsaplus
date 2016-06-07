@extends('layouts.master')
@section('title', 'Registrierung')

@section('content')
  <div class="container">
    <h2>Registrierung</h2>

    <ol class="breadcrumbs">
      <li class="active">1. Begrüßung</li>
      <li>2. Zusage</li>
      <li>3. Daten</li>
    </ol>

    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
      </div>
    </div>

    <p>Sehr geehrter Teilnehmer, sehr geehrte Teilnehmerin,</p>

    <p>im Rahmen unseres Nachsorgeprogramms GSA-Online plus stelle ich mich hiermit als Ihr Online-Therapeut vor, der Sie nach Abschluss Ihrer stationären Rehabilitationsbehandlung über 12 Wochen hinweg bei Ihrem beruflichen Wiedereinstieg begleiten und unterstützen wird. Ich möchte Sie anregen, mir Ihre Erwartungen und Erfahrungen bei der Rückkehr an den Arbeitsplatz in Form von wöchentlichen Tagebucheinträgen schriftlich mitzuteilen. Aus wissenschaftlichen Studien wissen wir, dass regelmäßiges Aufschreiben hilft, besser mit Belastungen und Stress umzugehen.</p>

    <p>Nach Entlassung aus der Rehaklinik werde ich Sie daher bitten, sich jede Woche zu einem festen Termin ca. 45 Minuten Zeit zu nehmen, um in Ihr persönliches Tagebuch zu schreiben. Sie werden dazu von mir jede Woche einen kurzen, auf Sie zugeschnittenen Schreibimpuls erhalten, worin ich Sie bitten werde, möglichst konkrete Begegnungen mit anderen Menschen aus Ihrem Arbeitsalltag zu schildern. Ich werde Ihren jeweiligen Tagebucheintrag dann in der Regel innerhalb von 24 Stunden lesen und beantworten. In meiner Antwort werde ich Ihnen Anregungen und Hinweise geben, die Ihnen helfen sollen, mit belastenden Situationen am Arbeitsplatz anders umzugehen.</p>

    <p>Im folgenden Anmeldeverfahren werden Sie nun gebeten, Ihre Zusage zur Teilnahme zu bestätigen, sich mit einem anonymen Benutzernamen und Passwort zu registrieren und einen persönlichen Schreibtag festzulegen.</p>

    <p>Ich freue mich darauf, Sie bei Ihrem beruflichen Wiedereinstieg begleiten zu dürfen.</p>

    <p>Herzliche Grüße,<br>
    Ihr Online-Therapeut</p>

    <div class="pull-right">
      <a class="btn btn-accent" href="/registration/agreement"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Weiter</a>
      <!-- TODO: better URLs
        <a ... href="/registration/agreement">
      -->
    </div>
  </div>
@endsection
