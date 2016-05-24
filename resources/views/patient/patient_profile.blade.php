@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">


    <h2>
      Profil von {{ $Patient['name'] }}

        @if($isTherapist)
        <small>
          Code: <code>{{ $Patient['code'] }}</code>,
          Status: <code>{{ $Patient['status'] }}</code>
        </small>
        @endif


    </h2>

    @include('patient.profile.notizen')
    @include('patient.profile.therapist')
    @include('patient.profile.schreibtag')
    @include('patient.profile.entlassungsdatum')
    @include('patient.profile.password')
    @include('patient.profile.persönliche_informationen')
    @include('patient.profile.intervention_beenden')

    <hr>
    <p>
      <a href="/" class="btn btn-default">Fertig</a>
    </p>


  </div>
@endsection
