<!-- purpose: reset password -->
@extends('layouts.master')
@section('title', 'Passwort Wiederherstellung - Schritt 2')

  @section('content')
    <div class="container">
      <br/>
      <form method="POST" action="/password/reset">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label for="email">Email</label>
          <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="EMail" required>
        </div>

        <div class="form-group">
          <label for="password">Passwort</label>
          <input name="password" type="password" class="form-control" placeholder="Passwort" required>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Passwort (Bestätigung)</label>
          <input name="password_confirmation" type="password" class="form-control" placeholder="Passwort (Bestätigung)" required>
        </div>

        <div>
          <button class="btn-primary btn pull-right" type="submit">
            Passwort zurücksetzen
          </button>
        </div>
      </form>
    </div>
  @endsection
