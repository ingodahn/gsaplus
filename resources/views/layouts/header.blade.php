<nav class="navbar navbar-default" role="navigation">
  <div class="container">
    <div class="navbar-header">
      @if($isLoggedIn)
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      @endif
      <a href="/">
        <img src="/img/logo.svg" alt="GSA Online Plus Logo" title="zur Startseite" class="nav-logo"></img>
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">

        @if($isLoggedIn)

          {{-- Patient --}}
          @if($isPatient)
            <li><a href="/Home">Mein Tagebuch</a></li>
          @endif

          {{-- Therapist --}}
          @if($isTherapist)
            <li><a href="/Home">Patientenliste</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin-Tools <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="/test" target="_blank">Konrollzentrum</a></li>
                <li><a href="/AdminUsers" target="_blank">Alle Nutzer</a></li>
                <li><a href="/AdminCodes" target="_blank">Alle Codes</a></li>
              </ul>
            </li>
          @endif

          {{-- Common --}}
          <li><a href="/info">Experteninformationen</a></li>

          {{-- User-Related --}}
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user{{$isTherapist ? "-md" : ""}}" aria-hidden="true"></i> {{$Name}} <span class="caret"></span></a>
            <ul class="dropdown-menu">
              @if($isPatient)
                <li><a href="/Profile/{{$Name}}">Mein Profil</a></li>
              @endif
              <li><a href="/Logout">Ausloggen</a></li>
            </ul>
          </li>

        @endif

      </ul>
    </div>

  </div>
</nav>
