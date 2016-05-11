@extends('layouts.master')
@section('title', 'Mail verfassen')

@section('content')
    <div class="container">

        <h2>Mail-Editor</h2>
        <div>
            Mails gehen an {{ $ListOfPatients }}.
        </div>
        <form action="/SendMail" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="list_of_names" value="{{ $ListOfPatients }}">
            <div class="form-group">
                <label for="subject" class="control-label">Betreff</label>
                <input name="mail_subject" type="text" class="form-control" placeholder="Wadde hadde dudeda?"></input>
            </div>
            <div class="form-group">
                <label for="message" class="control-label">Nachricht</label>
                <textarea name="mail_body" rows="5" class="form-control js-auto-size" placeholder="Sehr geehrter Dr Ogen..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary pull-right">Absenden</button>
            <a class="btn btn-primary pull-right" style="margin-right: 10px;" href="/Home">Zurück</a>
        </form>

    </div>
@endsection
