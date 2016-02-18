@extends('layouts.master')
@section('title', 'Kontaktformular')

@section('content')
    <div class="container">

        <h2>Kontaktformular</h2>

        <p>
          Nutzen sie dieses Formular um eine E-Mail an <a href="mailto:PapstDonB@Googlemail.com">PapstDonB@Googlemail.com</a> zu senden.
        </p>

        <form action="/SendMessage" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="eMail" class="control-label">E-Mail</label>
            <input name="eMail" type="text" class="form-control" placeholder="dr_xtc@drogen24.onion"></input>
          </div>
          <div class="form-group">
            <label for="subject" class="control-label">Betreff</label>
            <input name="subject" type="text" class="form-control" placeholder="Wadde hadde dudeda?"></input>
          </div>
          <div class="form-group">
            <label for="message" class="control-label">Nachricht</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Sehr geehrter Dr Ogen..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary pull-right">Absenden</button>
        </form>

    </div>
@endsection
