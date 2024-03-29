@extends('layouts.master')
@section('title', 'Willkommen')
@section('additional-head')
  <script src="/js/intro-modal.js" charset="utf-8"></script>
@endsection

@section('content')

  <div id="intro-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Intro Video" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body modal-video videoWrapper">
          <iframe width="560" height="315" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:void(0)" class="btn btn-danger" data-dismiss="modal">Schließen</a>
      </div>
    </div>
  </div>

  <div class="parallax-window parallax-window-nav vertical-center" data-parallax="scroll" data-image-src="/img/gsa-online-plus-background-1920px.jpg">
    <a href="javascript:openModal()"><i class="fa fa-youtube-play"></i></a>
  </div>

  <div class="container register-login">

    <div class="row vdivide">
      <div class="col-md-4">
        <h2>Registrierung</h2>
        @if ($RegistrationPossible)
        <form action="/register" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="Code">Persönlicher Code</label>
            <a href="javascript:void(0)" tabindex="0" tabindex="0" data-toggle="popover" role="button" data-placement="top" data-trigger="focus" data-html="true"  title="Persönlicher Code" data-content="Die Registrierung ist nur mit einem Code möglich, den Sie im Rahmen des GSA-Online plus Programms erhalten haben.<br>Bei Problemen wenden Sie sich bitte an unser Team. Nutzen Sie hierzu den Menüpunkt 'Kontakt' unten.">
              <i class="fa fa-question-circle"></i>
            </a>

            <input name="Code" class="form-control" placeholder="Code">
          </div>
          <button type="submit" class="btn btn-primary">zur Registrierung</button>
        </form>
        @else
          <p>
            Leider sind derzeit alle verfügbaren Plätze in der Online-Nachsorge vergeben. Wir hoffen, Ihnen in den nächsten Wochen einen freien Platz anbieten zu können.
          </p>
		  <p>
            Nutzen Sie das Kontaktformular um Ihre Kontaktdaten zu hinterlegen. Wir werden Ihnen Materialien zur Nachsorge sowie Informationen über die freien Plätze zusenden.
          </p>
          <a href="/ContactTeam" class="btn btn-warning">Team kontaktieren</a>
        @endif
      </div>
      <div class="col-md-8">
        <h2>Login</h2>
        <form action="/Login" method="post" data-parsley-validate>
          {{ csrf_field() }}
          <div class="form-group">
            <label for="name">Benutzername</label>
            <input name="name" class="form-control" placeholder="Benutzername" autocomplete="off" required pattern="^[a-zA-Z0-9\.\-_]+$">
          </div>
          <div class="form-group">
            <label for="password">Passwort</label>
            <input name="password" type="password" class="form-control" placeholder="Passwort" required>
          </div>
          <div class="checkbox">
            <label>
              <input name="remember" type="checkbox"> Eingeloggt bleiben
            </label>
            <a href="javascript:void(0)" tabindex="0" data-toggle="popover" data-placement="top" data-trigger="focus" data-html="true" title="Eingeloggt bleiben" data-content="Wenn diese Funktion aktiviert wird, bleiben Sie über einen längeren Zeitraum eingeloggt und gelangen direkt auf Ihre persönliche Seite und sehen dieses Anmeldebildschirm nicht. Sie bleiben so lange eingeloggt, bis Sie sich aktiv ausloggen.<br><strong>Achtung:</strong> Wir raten davon ab, diese Funktion zu aktivieren, wenn wenn Sie sich an einem öffentlichen oder gemeinsam genutzten Gerät einloggen.">
              <i class="fa fa-question-circle"></i>
            </a>
          </div>
          <button type="submit" class="btn btn-primary">Login</button> <a href="/password/email">Passwort vergessen?</a>
        </form>
      </div>
    </div>
  </div>

  {{-- <div class="parallax-window" data-parallax="scroll" data-image-src="/img/bg.jpg"></div> --}}

@endsection
