@extends('layouts.master')
@section('title', 'Kontaktformular')

@section('content')
    <div class="container">

        <h2>Kontaktformular</h2>

        <p>
          Nutzen sie dieses Formular um eine E-Mail an <a href="mailto:PapstDonB@Googlemail.com">PapstDonB@Googlemail.com</a> zu senden.
        </p>

        <form action="/SendMessage" method="post" enctype="text/plain">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input name="name" type="text" placeholder="Dr. XTC">
          </div>
          <div class="form-group">
            <label for="mail" class="control-label">E-Mail</label>
            <input name="mail" type="text" placeholder="dr_xtc@drogen24.onion">
          </div>
          <div class="form-group">
            <label for="text" class="control-label">Nachricht</label>
            <input name="text" type="text" placeholder="Sehr geehrter Dr Ogen..." size="50"><br><br>
          </div>
          <button type="submit" class="btn btn-primary pull-right">Absenden</button>
        </form>

    </div>
@endsection
