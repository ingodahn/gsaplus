@extends('layouts.master')
@section('title', 'Willkommen')

@section('content')

  <div id="intro-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Intro Video" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body modal-video videoWrapper">
          <iframe width="560" height="315" frameborder="0" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>

  <script>
    function openModal() {
      var src = 'http://www.youtube.com/v/LS-VPyLaJFM&amp;autoplay=1';
      $('#intro-modal').modal('show');
      $('#intro-modal iframe').attr('src', src);
    }

    $('#intro-modal').on('hidden.bs.modal', function () {
      $('#intro-modal iframe').removeAttr('src');
    });
  </script>

  <div class="parallax-window parallax-window-nav vertical-center" data-parallax="scroll" data-image-src="/img/bg2.jpg">
    <a href="javascript:openModal()"><i class="fa fa-youtube-play"></i></a>
  </div>

  <div class="container register-login">

    <div class="row vdivide">
      <div class="col-md-4">
        <h2>Registrierung</h2>
        @if ($RegistrationPossible)
        <form action="/StartRegistration" method="post">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="Code">Persönlicher Code</label>
            <a href="#"><i class="fa fa-question-circle"></i></a>

            <input name="Code" class="form-control" placeholder="Code">
          </div>
          <button type="submit" class="btn btn-primary">zur Registrierung</button>
        </form>
        @else
          <p>
            Zur Zeit sind keine Plätze frei, yadda yadda. Sie können jedoch das Team kontaktieren, bla bla.
          </p>
          <a href="/ContactTeam" class="btn btn-warning">Team kontaktieren</a>
        @endif
      </div>
      <div class="col-md-8">
        <h2>Login</h2>
        <form action="/CheckLoginPassword" method="post" data-parsley-validate>
          {{ csrf_field() }}
          <div class="form-group">
            <label for="NameOrEmail">Benutzername oder E-Mail Adresse</label>
            <input name="NameOrEmail" class="form-control" placeholder="Benutzername oder E-Mail" required>
          </div>
          <div class="form-group">
            <label for="Password">Passwort</label>
            <input name="Password" type="password" class="form-control" placeholder="Passwort" required>
          </div>
          <div class="checkbox">
            <label>
              <input name="StayLoggedIn" type="checkbox"> Eingeloggt bleiben
            </label>
            <a href="#"><i class="fa fa-question-circle"></i></a>
          </div>
          <button type="submit" class="btn btn-primary">Login</button> <a href="#">Passwort vergessen?</a>
        </form>
      </div>
    </div>
  </div>

  {{-- <div class="parallax-window" data-parallax="scroll" data-image-src="/img/bg.jpg"></div> --}}

@endsection
