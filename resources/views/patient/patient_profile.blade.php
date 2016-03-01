@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">

    <h2>{{ $Name }} <small>({{ $Patient['code'] }})</small></h2>


    <p>
      Dies ist das Profil von <em>{{ $Name }}</em> aus der Sicht des Benutzers mit der Rolle {{ $Role }}.
    </p>


    @if ($Role == 'therapist')
      <h3>Notizen des Therapeuten</h3>
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
    </form>

    <hr>
    <p>
      <a href="/Logout" class="btn btn-warning">Ausloggen.</a>
    </p>

  </div>
@endsection
