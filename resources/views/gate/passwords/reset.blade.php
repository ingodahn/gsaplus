<!-- purpose: reset password -->
@extends('layouts.master')

@section('additional-head')
  <script src="/js/zxcvbn.js" charset="utf-8"></script>
  <script src="/js/zxcvbn-evaluate.js" charset="utf-8"></script>
@endsection

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
          <div class="input-group">
            <input name="password" id="password" type="password" class="form-control" placeholder="Passwort" required minlength="6" aria-describedby="strength-addon">
            <span class="input-group-addon" id="strength-addon"></span>
          </div>
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
