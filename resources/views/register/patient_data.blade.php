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

    <div class="bs-callout bs-callout-info">
      <b>Hinweis: </b>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </div>

    <form data-toggle="validator" role="form" action="#" method="post">
      <h3>Ihre Daten</h3>

      <div class="form-group">
        <label for="name" class="control-label">Name</label>
        <input type="text" class="form-control" id="name" placeholder="Hans Maulwurf" required>
      </div>

      <div class="form-group">
        <label for="eMail" class="control-label">E-Mail Adresse</label>
        <input type="email" class="form-control" id="eMail" placeholder="hansmaul@springfield.net" data-error="Bruh, that email address aint valid" required>
      </div>

      <div class="form-group">
        <label for="password" class="control-label">Passwort</label>
        <div class="form-inline row">
          <div class="form-group col-sm-6">
            <input type="password" data-minlength="6" class="form-control width-100" id="password" placeholder="hunter2" required>
            <span class="help-block">Ihr Passwort muss mindestens 6 Zeichen lang sein</span>
          </div>
          <div class="form-group col-sm-6">
            <input type="password" class="form-control width-100" id="passwordConfirm" data-match="#password" data-match-error="Die Passwörter stimmen nicht überein" placeholder="Passwort wiederholen" required>
          </div>
        </div>
      </div>

      <h3>Blogtag</h3>

      <div class="form-group">
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="MONDAY" required>
            Montag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="TUESDAY" required>
            Dienstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="WEDNESDAY" required>
            Mittwoch
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="THURSDAY" required>
            Donnerstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="FRIDAY" required>
            Freitag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="SATURADAY" required>
            Samstag
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="day_of_week" value="SUNDAY" required>
            Sonntag
          </label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary pull-right">Absenden</button>
      </div>
    </form>

  </div>
@endsection
