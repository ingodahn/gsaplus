<!-- purpose: request a password reset link -->
@extends('layouts.master')
@section('title', 'Passwort Wiederherstellung - Schritt 1')

@section('content')
    <div class="container">
        <br/>
        <form method="POST" action="/password/email">
            {!! csrf_field() !!}

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
