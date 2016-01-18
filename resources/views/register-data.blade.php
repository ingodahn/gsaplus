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
    <form data-toggle="validator" role="form" action="#" method="post">

      <h3>Schreibtag</h3>

      <div class="bs-callout bs-callout-info">
        <p>Bitte wählen Sie einen Wochentag, an dem Sie in den nächsten 12 Wochen regelmäßig Zeit haben, Ihren persönlichen Tagebucheintrag zu schreiben und einige kurze Fragen zu beantworten.  Sie haben unter „Mein Profil“ auch später noch die Gelegenheit, Ihren Schreibtag ein mal auf einen anderen Tag zu verlegen.</p>

        <p>Ihr Online-Therapeut wird Ihnen in der Regel innerhalb von 24 Stunden auf Ihren Eintrag antworten.</p>
      </div>

      <p>Ich schreibe meinen Tagebuch in Zukunft wöchentlich am:</p>

      <div class="form-group">
        <div class="radio">
          <label>
            <input type="radio" name="day" value="MONDAY" required>
            Montag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="TUESDAY" required>
            Dienstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="WEDNESDAY" required>
            Mittwoch
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="THURSDAY" required>
            Donnerstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="FRIDAY" required>
            Freitag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="SATURADAY" required>
            Samstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day" value="SUNDAY" required>
            Sonntag
          </label>
        </div>
      </div>


      <h3>Ihre Daten</h3>

      <div class="bs-callout bs-callout-info">
        <p><strong>Bleiben Sie anonym!</strong> Zur Wahrung des Datenschutzes ist es notwendig, dass Sie einen Benutzernamen wählen, der <em>nicht</em> Ihrem vollständigen Vor- und Zunamen entspricht.</p>

        <p>Ihr Online-Therapeut wird Sie in Zukunft unter Ihrem Benutzernamen ansprechen und keinen Bezug zu Ihrem echten Namen herstellen können. Die E-Mail Adresse ist für den Therapeuten nicht sichtbar.</p>
      </div>

      <p>Bitte wählen Sie einen Benutzernamen und ein Passwort und geben Sie eine gültige E-Mail Adresse ein:</p>

      <div class="form-group">
        <label for="registerName" class="control-label">Name</label>
        <input type="text" class="form-control" id="registerName" placeholder="Hans Maulwurf" required>
      </div>

      <div class="form-group">
        <label for="registerEmail" class="control-label">E-Mail Adresse</label>
        <input type="email" class="form-control" id="registerEmail" placeholder="hansmaul@springfield.net" data-error="Bruh, that email address aint valid" required>
      </div>

      <div class="form-group">
        <label for="registerPassword" class="control-label">Passwort</label>
        <div class="form-inline row">
          <div class="form-group col-sm-6">
            <input type="password" data-minlength="6" class="form-control width-100" id="registerPassword" placeholder="hunter2" required>
            <span class="help-block">Ihr Passwort muss mindestens 6 Zeichen lang sein</span>
          </div>
          <div class="form-group col-sm-6">
            <input type="password" class="form-control width-100" id="registerPasswordConfirm" data-match="#registerPassword" data-match-error="Die Passwörter stimmen nicht überein" placeholder="Passwort wiederholen" required>
          </div>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Absenden</button>
      </div>
    </form>

  </div>
@endsection
