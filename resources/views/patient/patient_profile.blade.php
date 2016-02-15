@extends('layouts.master')
@section('title', 'Profil')

@section('content')
  <div class="container">

    <h2>Profil</h2>
    <p>
      Dies ist das Profil von <em>{{ $Name }}</em> aus der Sicht des Benutzers mit der Rolle {{ $Role }}.
    </p>

    <p>
      <a href="/Home" class="btn btn-warning">Abbrechen</a>
    </p>

  </div>
@endsection
