@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">

    <h2>Profil</h2>
    <p>
      Dies ist das Profil von <em>{{ $Name }}</em> aus der Sicht des Benutzers mit der Rolle {{ $Role }}.
    </p>
    @if ($Role == 'therapist')
      <h3>Notizen des Therapeuten</h3>
      <p>{{ $Patient['notes'] }}</p>
    @else
      <p>Die Notizen des Therapeuten bleiben unsichtbar</p>
    @endif
    <p>
      <a href="/Home" class="btn btn-warning">Abbrechen</a>
    </p>

  </div>
@endsection
