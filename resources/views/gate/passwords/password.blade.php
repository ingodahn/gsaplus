<!-- purpose: request a password reset link -->
@extends('layouts.master')
@section('title', 'Passwort Wiederherstellung - Schritt 1')

  @section('content')
    <div class="container">

      <h2>Passwort zurücksetzen</h2>

      <form method="POST" action="/password/email">
        {!! csrf_field() !!}

        <p>Wenn Sie Ihr Passwort zurücksetzen möchten, geben Sie die E-Mail-Adresse an, mit der Sie sich bei GSA online plus registriert haben.</p>

        <p>Sie erhalten eine E-Mail mit den Link, den Sie zur Wiederherstellung Ihres Passworts nutzen können.</p>
        
        <div class="form-group">
          <label for="email">E-Mail Adresse</label>
          <input name="email" type="email" value="{{ old('email') }}" class="form-control" placeholder="Ihre E-Mail Adresse" required>
        </div>

        @if (Session::get('status'))
          <p class="alert-success">{{Session::get('status')}}</p>
        @endif

        <div>
          <button class="btn-primary btn pull-right" type="submit">
            Link anfordern
          </button>
        </div>
      </form>
    </div>
  @endsection
