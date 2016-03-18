@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">


    <h2>
      Profil von {{ $PatientName }}
      <small>
        (
        @if($isTherapist)
          code: <code>{{ $Patient['code'] }}</code>,
        @endif
        status: <code>{{ $Patient['status'] }}</code>
        )
      </small>
    </h2>


    <p>
      <a href="/Logout" class="btn btn-warning">Ausloggen</a>
    </p>

    @if ($isTherapist)
      <hr>
      <h3>Notizen</h3>
      <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/notes" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="notes" class="control-label">Notizen</label>
          <textarea name="notes" rows="5" class="form-control" placeholder="Hat eine zu krasse Meinung zu Earl Grey.">{{ $Patient['notes'] }}</textarea>
        </div>
        <p>
          <div class="form-group">
            <button type="submit" class="btn">Notizen ändern</button>
          </div>
        </p>
      </form>
    @endif


    @if($isTherapist)
      <hr>
      <h3>Therapeut</h3>
      <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/therapist" method="post" }}>
        {{ csrf_field() }}
        <div class="form-group">
          <label for="therapist" class="control-label">Therapeut</label>
          <select name="therapist" class="form-control" required>
            <option>{{ $Patient['therapist'] }}</option>
            @foreach(array_diff($Patient['listOfTherapists'], [$Patient['therapist']]) as $therapist)
              <option>{{$therapist}}</option>
            @endforeach
          </select>
        </div>
        <p>
          <div class="form-group">
            <button type="submit" class="btn">Therapeut setzen</button>
          </div>
        </p>
      </form>
    @endif


    <hr>
    <h3>Tagebuchtag</h3>
    <p>
      Der aktuelle Tagebuchtag ist <strong>{{ $Patient['assignment_day'] }}</strong> und es verbleiben noch <strong>{{ $Patient['assignmentDayChangesLeft'] }}</strong>
      @if($Patient['assignmentDayChangesLeft'] == 1)
        Änderung.
      @else
        Änderungen.
      @endif
    </p>
    @if($isPatient && $Patient['assignmentDayChangesLeft'] > 0)
      <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/day_of_week" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="day_of_week" class="control-label">Wochentag</label>
          <a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" title="Warum sind nicht alle Wochentage wählbar?" data-content="Wir möchten, dass Sie nach dem Schreiben Ihres Blog möglichst innerhalb von 24 h eine Rückmeldung Ihres Online-Therapeuten erhalten. Da wir dies jedoch nur von Montag bis Freitag mit begrenzten Kapazitäten zusagen können, sind nicht alle Tage als Schreibtage wählbar.">
            <i class="fa fa-question-circle"></i>
          </a>
          <select name="day_of_week" class="form-control" required>
            <option>{{ $Patient['assignment_day'] }}</option>
            @foreach(array_diff($Patient['available_days'], [$Patient['assignment_day']]) as $day)
              <option>{{ $day }}</option>
            @endforeach
          </select>
        </div>
        <p>
          <div class="form-group">
            <button type="submit" class="btn">Tagebuchtag setzen</button>
          </div>
        </p>
      </form>
    @endif


    @if($isTherapist)
      <hr>
      <h3>Entlassungsdatum</h3>
      @if($Patient['status'] >= "P030")
        <p>
          Das Entlassungsdatum war {{ $Patient['dateFromClinics'] }}.
        </p>
      @else
        <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/dateFromClinics" method="post">
          {{ csrf_field() }}
            <div class="form-group">
              <div class='input-group date' id='datetimepicker1'>
                {{-- <label for="dateFromClinics" class="control-label">Entlassungsdatum</label> --}}
                <input name="dateFromClinics" type='text' value="{{ $Patient['dateFromClinics'] }}" class="form-control" required>
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </input>
              </div>
            </div>
          <script type="text/javascript">
              $(function () {
                  $('#datetimepicker1').datetimepicker({
                    locale: 'de',
                    format: 'DD.MM.YYYY'
                  });
              });
          </script>
          <p>
            <div class="form-group">
              <button type="submit" class="btn">Entlassungsdatum setzen</button>
            </div>
          </p>
        </form>
      @endif
    @endif

    @if($isPatient)
      <hr>
      <h3>Passwort ändern</h3>
      <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/password" method="post">
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
    @endif

    <hr>
    <h3>Persönliche Informationen</h3>
    @if($isPatient)
      <form data-parsley-validate role="form" action="/patient/{{$PatientName}}/personalInformation" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="personalInformation" class="control-label">(nur für Therapeuten sichtbar)</label>
          <textarea name="personalInformation" rows="5" class="form-control" placeholder="Ich habe eine Meinung zu Earl Grey.">{{ $Patient['personalInformation'] }}</textarea>
        </div>
        <p>
          <div class="form-group">
            <button type="submit" class="btn">Informationen ändern</button>
          </div>
        </p>
      </form>
    @else
      <p>
        {{ $Patient['personalInformation'] }}
      </p>
    @endif


    @if($isTherapist)
      <hr>
      <h3>Intervention beenden</h3>
      <p>
        <a href="/patient/{{$PatientName}}/cancelIntervention" class="btn btn-danger">Intervention beenden</a>
      </p>
    @endif

    <hr>
    <p>
      <a href="/" class="btn btn-default">Fertig</a>
    </p>


  </div>
@endsection
