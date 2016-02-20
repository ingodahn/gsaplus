@extends('layouts.master')
@section('title', 'Registrierung')

@section('content')
  <div class="container">

    <h2>Registrierung</h2>

    <ol class="breadcrumbs">
      <li class="done">1. Begrüßung</li>
      <li class="done">2. Zusage</li>
      <li class="active">3. Daten</li>
    </ol>

    {{-- All active form content must stay in this form for frontend and backend processing --}}
    <form id="registration-form" data-parsley-validate role="form" action="/registration/form" method="post">
      {{ csrf_field() }}

      <h3>Schreibtag</h3>

      <div class="bs-callout bs-callout-info">
        <p>Bitte wählen Sie einen Wochentag, an dem Sie in den nächsten 12 Wochen regelmäßig Zeit haben, Ihren persönlichen Tagebucheintrag zu schreiben und einige kurze Fragen zu beantworten.  Sie haben unter „Mein Profil“ auch später noch die Gelegenheit, Ihren Schreibtag ein mal auf einen anderen Tag zu verlegen.</p>

        <p>Ihr Online-Therapeut wird Ihnen in der Regel innerhalb von 24 Stunden auf Ihren Eintrag antworten.</p>
      </div>

      <p>Ich schreibe meinen Tagebuch in Zukunft wöchentlich am:</p>

      <div class="form-group">
        <label for="day_of_week" class="control-label">Wochentag</label>
        <a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" title="Warum sind nicht alle Wochentage wählbar?" data-content="Wir möchten, dass Sie nach dem Schreiben Ihres Blog möglichst innerhalb von 24 h eine Rückmeldung Ihres Online-Therapeuten erhalten. Da wir dies jedoch nur von Montag bis Freitag mit begrenzten Kapazitäten zusagen können, sind nicht alle Tage als Schreibtage wählbar.">
          <i class="fa fa-question-circle"></i>
        </a>
        <select name="day_of_week" class="form-control" required>
          @@foreach($DayOfWeek as $available_day)
            <option>{{$available_day}}</option>
          @endforeach
        </select>
      </div>


      <h3>Ihre Daten</h3>

      <div class="bs-callout bs-callout-info">
        <p><strong>Bleiben Sie anonym!</strong> Zur Wahrung des Datenschutzes ist es notwendig, dass Sie einen Benutzernamen wählen, der <em>nicht</em> Ihrem vollständigen Vor- und Zunamen entspricht.</p>

        <p>Ihr Online-Therapeut wird Sie in Zukunft unter Ihrem Benutzernamen ansprechen und keinen Bezug zu Ihrem echten Namen herstellen können. Die E-Mail Adresse ist für den Therapeuten nicht sichtbar.</p>
      </div>

      <p>Bitte wählen Sie einen Benutzernamen und ein Passwort und geben Sie eine gültige E-Mail Adresse ein:</p>

      <div class="form-group">
        <label for="name" class="control-label">Benutzername</label>
        <input name="name" type="text" class="form-control" placeholder="mrhyde63" required>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label for="email" class="control-label">E-Mail Adresse</label>
          <input name="email" id="email" type="email" class="form-control width-100" placeholder="w.meyer@web.de" required>
        </div>

        <div class="form-group col-sm-6">
          <label class="control-label">Wiederholen</label>
          <input type="email" placeholder="w.meyer@web.de" class="form-control width-100" required data-parsley-equalto="#email">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label for="password" class="control-label">Passwort</label>
          <input name="password" id="password" type="password" class="form-control width-100" placeholder="hunter2 (mindestens 6 Zeichen)" required minlength="6">
        </div>

        <div class="form-group col-sm-6">
          <label class="control-label">Wiederholen</label>
          <input type="password" class="form-control width-100" placeholder="hunter2" required minlength="6" data-parsley-equalto="#password">
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Absenden</button>
      </div>
    </form>

  </div>
@endsection
