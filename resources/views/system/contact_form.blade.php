@extends('layouts.master')
@section('title', 'Kontaktformular')

@section('content')
    <div class="container">

        <h2>Kontaktformular</h2>

        <p>
          Nutzen sie dieses Formular um eine E-Mail an <a href="mailto:kontakt@gsa-online-plus.net">kontakt@gsa-online-plus.net</a> zu senden.
        </p>

        <form action="/SendMessage" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="eMail" class="control-label">E-Mail</label>
            <input name="eMail" type="text" class="form-control" placeholder="rapunzel@turm.de"></input>
          </div>
          <div class="form-group">
            <label for="subject" class="control-label">Betreff</label>
            <input name="subject" type="text" class="form-control" placeholder="Problem und mögliche Lösung"></input>
          </div>
          <div class="form-group">
            <label for="message" class="control-label">Nachricht</label>
            <textarea name="message" rows="5" class="form-control" placeholder="Ich will gerne mit dir gehen, aber ich weiß nicht, wie ich herabkommen kann. Wenn du kommst, so bring jedesmal einen Strang Seide mit, daraus will ich eine Leiter flechten, und wenn die fertig ist, so steige ich herunter, und du nimmst mich auf dein Pferd."></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Absenden</button>
          <a href="/Home" class="btn btn-default">Abbrechen</a>
        </form>

    </div>
@endsection
