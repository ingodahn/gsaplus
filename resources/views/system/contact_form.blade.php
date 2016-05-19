@extends('layouts.master')
@section('title', 'Kontaktformular')

@section('content')
    <div class="container">

        <h2>Kontaktformular</h2>

        <p>
          Nutzen sie dieses Formular um eine E-Mail an <a href="mailto:online-nachsorge@unimedizin-mainz.de">online-nachsorge@unimedizin-mainz.de</a> zu senden.
        </p>

        <form action="/SendMessage" method="post" data-parsley-validate>
          {{ csrf_field() }}
          <div class="form-group">
            <label for="eMail" class="control-label">E-Mail</label>
            <input name="eMail" type="email" class="form-control" placeholder="mail@domain.de" required></input>
          </div>
          <div class="form-group">
            <label for="subject" class="control-label">Betreff</label>
            <input name="subject" type="text" class="form-control" placeholder="Betreff der E-Mail"></input>
          </div>
          <div class="form-group">
            <label for="message" class="control-label">Nachricht</label>
            <textarea name="message" rows="5" class="form-control js-auto-size" placeholder="Beschreiben Sie Ihr Anliegen" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary pull-right">Absenden</button>
        </form>

    </div>
@endsection
