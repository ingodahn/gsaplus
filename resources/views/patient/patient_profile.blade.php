@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">

    <h2>
      {{ $Name }}
      <small>
        (code: <code>{{ $Patient['code'] }}</code>, status: <code>{{ $Patient['status'] }}</code>)
      </small>
    </h2>


    <p>
      Dies ist das Profil von <strong>{{ $Name }}</strong> aus der Sicht des Benutzers mit der Rolle <code>{{ $Role }}</code>.
    </p>

    <hr>
    <h3>Notizen des Therapeuten</h3>
    @if ($Role == 'therapist')
      <p>{{ $Patient['notes'] }}</p>
    @else
      <p>Die Notizen des Therapeuten bleiben unsichtbar</p>
    @endif


    <hr>
    <h3>Entlassungsdatum setzen</h3>
    <form data-parsley-validate role="form" action="/patient/{{$Name}}/dateFromClinics" method="post">
      {{ csrf_field() }}
        <div class="form-group">
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' class="form-control" />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
      <script type="text/javascript">
          $(function () {
              $('#datetimepicker1').datetimepicker();
          });
      </script>
      <p>
        <div class="form-group">
          <button type="submit" class="btn">Entlassungsdatum setzen</button>
        </div>
      </p>
    </form>


    <hr>
    <h3>Passwort ändern</h3>
    <form data-parsley-validate role="form" action="/patient/{{$Name}}/password" method="post">
      {{ csrf_field() }}
      <p>
        <label for="password" class="control-label">Altes Passwort</label>
        <input name="oldPassword" type="password" class="form-control" placeholder="hunter2" required minlength="6">
      </p>
      <div class="row">
        <div class="form-group col-sm-6">
          <label for="password" class="control-label">Passwort</label>
          <input name="newPassword" id="password" type="password" class="form-control width-100" placeholder="hunter3 (mindestens 6 Zeichen)" required minlength="6">
        </div>
        <div class="form-group col-sm-6">
          <label class="control-label">Wiederholen</label>
          <input type="password" class="form-control width-100" placeholder="hunter3" required minlength="6" data-parsley-equalto="#password">
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn">Passwort ändern</button>
      </div>
    </form>


    <hr>
    <h3>Persönliche Informationen</h3>
    <form data-parsley-validate role="form" action="/patient/{{$Name}}/personalInformation" method="post">
      <div class="form-group">
        <label for="personalInformation" class="control-label">Informationen</label>
        <textarea name="personalInformation" rows="5" class="form-control" placeholder="Hat eine Meinung zu Earl Grey."></textarea>
      </div>
      <p>
        <div class="form-group">
          <button type="submit" class="btn">Informationen ändern</button>
        </div>
      </p>
    </form>

    <hr>
    <p>
      <a href="/Logout" class="btn btn-warning">Ausloggen.</a>
    </p>

  </div>
@endsection
