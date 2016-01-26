@extends('layouts.master')
@section('title', 'Willkommen')

@section('content')
  <div class="container greeting">
  <p>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
  </p>
  </div>

  <div class="jumbotron jumbotron-video">
  <div class="container">
    <div class="videoWrapper">
      <iframe width="560" height="315" src="https://www.youtube.com/embed/X9otDixAtFw" frameborder="0" allowfullscreen></iframe>
    </div>
  </div>
  </div>

  <div class="container">

    <div class="row vdivide">
      <div class="col-md-4">
        <h2>Registrierung</h2>
        <form action="/StartRegistration" method="post">
          <div class="form-group">
            <label for="Code">Persönlicher Code</label>
            <a href="#"><i class="fa fa-question-circle"></i></a>

            <input name="Code" class="form-control" placeholder="Code">
          </div>
          <button type="submit" class="btn btn-primary">zur Registrierung</button>
        </form>
      </div>
      <div class="col-md-8">
        <h2>Login</h2>
        <form>
          <div class="form-group">
            <label for="exampleInputEmail1">Benutzername oder E-Mail Adresse</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="E-Mail">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Passwort</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Passwort">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> Eingeloggt bleiben
            </label>
            <a href="#"><i class="fa fa-question-circle"></i></a>
          </div>
          <button type="submit" class="btn btn-primary">Login</button> <a href="#">Passwort vergessen?</a>
        </form>
      </div>
    </div>
  </div>
@endsection
