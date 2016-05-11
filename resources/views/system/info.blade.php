@extends('layouts.master')
@section('title', 'Experteninformationen')



@section('content')

  {{-- Add smooth scrolling --}}
  <script type="text/javascript">
    $(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 1000);
          return false;
        }
      }
    });
    });
  </script>

  <div class="container">
    <h2>Hintergrundinformationen zum GSA online plus</h2>

    <p>
      In Form von kurzen Videos erhalten Sie auf dieser Seite Hintergrundinformationen zum GSA online plus.
    </p>

    <div id="toc" class="list-group">
      <a href="#video-1" class="list-group-item">
        <h4 class="list-group-item-heading">Wer sind wir?</h4>
        <p class="list-group-item-text">Begrüßung und Angebot der online Nachsorge</p>
      </a>
      <a href="#video-2" class="list-group-item">
        <h4 class="list-group-item-heading">Rückkehr an den Arbeitsplatz</h4>
        <p class="list-group-item-text">Warum onlinebasierte Unterstützung bei der Wiedereingliederung?</p>
      </a>
      <a href="#video-3" class="list-group-item">
        <h4 class="list-group-item-heading">Ablauf und Durchführung</h4>
        <p class="list-group-item-text">Wie läuft die online Nachsorge ab?</p>
      </a>
      <a href="#video-4" class="list-group-item">
        <h4 class="list-group-item-heading">Die ZBKT Methode</h4>
        <p class="list-group-item-text">Welche Grundlage hat die online Nachsorge?</p>
      </a>
      <a href="#video-5" class="list-group-item">
        <h4 class="list-group-item-heading">Ein Beispiel</h4>
        <p class="list-group-item-text">Eine ganz normale Wiedereingliederung</p>
      </a>
      <a href="#video-6" class="list-group-item">
        <h4 class="list-group-item-heading">Am Ball Bleiben</h4>
        <p class="list-group-item-text">Warum ist es wichtig dabei zu bleiben?</p>
      </a>
    </div>

    <div class="info-video" id="video-1">
      <h3>Wer sind wir?</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Begrüßung und Angebot der online Nachsorge
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/RvOnXh3NN9w" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-2">
      <h3>Rückkehr an den Arbeitsplatz</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Warum onlinebasierte Unterstützung bei der Wiedereingliederung?
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/ao8L-0nSYzg" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-3">
      <h3>Ablauf und Durchführung</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Wie läuft die online Nachsorge ab?
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/sNhhvQGsMEc" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-4">
      <h3>Die ZBKT Methode</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Welche Grundlage hat die online Nachsorge?
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/QOCaacO8wus" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-5">
      <h3>Ein Beispiel</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Eine ganz normale Wiedereingliederung
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/4_aOIA-vyBo" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-6">
      <h3>Am Ball Bleiben</h3>
      <div class="pull-right">
        <a href="#body"><span class="glyphicon glyphicon-arrow-up"></span> zum Seitenanfang</a>
      </div>
      <p>
        Warum ist es wichtig dabei zu bleiben?
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/V9_PjdU3Mpo" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

  </div>
@endsection
