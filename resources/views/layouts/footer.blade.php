<footer class="footer">
  <div class="container">
    <nav class="navbar navbar-footer">

          <ul class="nav navbar-nav">
            <li><a href="/about">Über das Projekt</a></li>
            <li><a href="/ContactTeam">Kontakt</a></li>
            <li><a href="/privacy">Datenschutz</a></li>
            <li><a href="/impressum">Impressum</a></li>
          </ul>
          @if($isLoggedIn)
            <a href="http://www.unimedizin-mainz.de/" target="_blank">
              <img src="/img/unimedizin-mainz-logo.svg" alt="Universitätsmedizin Mainz Logo" title="zur Website der Universitätsmedizin Mainz" class="unimed-logo-footer">
            </a>
          @endif

          @if(!$isLoggedIn)
            <a href="http://www.unimedizin-mainz.de/" target="_blank">
              <img src="/img/unimedizin-mainz-logo.svg" alt="Universitätsmedizin Mainz Logo" title="zur Website der Universitätsmedizin Mainz" class="unimed-logo-footer-startpage">
            </a>
          @endif
    </nav>
  </div>
</footer>
