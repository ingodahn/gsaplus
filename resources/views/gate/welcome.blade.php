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

    <p>im Rahmen unseres internetbasierten Nachsorgeprogramms stelle ich mich hiermit als Ihr Online-Therapeut vor, der Sie nun die nächsten 12 Wochen bei Ihrem beruflichen Wiedereinstieg begleiten und unterstützen wird. Ich möchte Sie anregen, mir Ihre Erwartungen und Erfahrungen bei der Rückkehr an den Arbeitsplatz in Form von wöchtentlichen Tagebucheinträgen schriftlich mitzuteilen. Aus wissenschaftlichen Studien wissen wir, dass regelmäßiges Aufschreiben hilft, besser mit Belastungen und Stress umzugehen.</p>

    <p>Ich möchte Sie daher bitten, sich jede Woche zu einem festen Termin ca. 45 Minuten Zeit zu nehmen, um in Ihr persönliches Tagebuch zu schreiben, d.h. konkrete Begegnungen mit anderen Menschen aus Ihrem Arbeitsalltag zu schildern. Es geht dabei um Ihre Wünsche und Erwartungen an andere Menschen und darum, wie die anderen und Sie selbst reagieren.</p>

    <p>Unterstützend  werden Sie von mir jede Woche einen kurzen, auf Sie zugeschnittenen  Schreibimpuls erhalten. Ich werde Ihren Tagebucheintrag dann in der Regel jeweils innerhalb von 24 Stunden lesen und beantworten. In meiner Antwort werde ich Ihnen Anregungen und Hinweise geben, die Ihnen helfen sollen, mit belastenden Situationen am Arbeitsplatz anders umzugehen.</p>

    <p>Sollten Sie zurzeit arbeitsunfähig sein, können Sie sich bei Ihren Schilderungen auch auf vergangene Situationen beziehen oder Ihre Erwartungen an die zukünftige Situation am Arbeitsplatz beschreiben.</p>

    <p>Im folgenden Anmeldeverfahren werden Sie nun gebeten, Ihre Zusage zur Teilnahme zu bestätigen, sich mit einem anonymen Benutzernamen und Passwort zu registrieren und einen persönlichen Schreibtag festzulegen.</p>

    <p>Ich freue mich darauf, Sie in den nächsten Wochen bei Ihrer beruflichen Rückkehr begleiten zu können.</p>

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
