@extends('layouts.master')
@section('title', 'Experteninformationen')

@section('content')
  <div class="container">
    <h2>Experteninformationen</h2>

    <div id="toc" class="list-group">
      <a href="#video-1" class="list-group-item">
        <h4 class="list-group-item-heading">Some video</h4>
        <p class="list-group-item-text">Sehen sie hier, wie ein Mann lacht.</p>
      </a>
      <a href="#video-2" class="list-group-item">
        <h4 class="list-group-item-heading">Another video</h4>
        <p class="list-group-item-text">Sehen sie hier, wie ein Mann weint.</p>
      </a>
      <a href="#video-3" class="list-group-item">
        <h4 class="list-group-item-heading">Awesome video</h4>
        <p class="list-group-item-text">Ein Mann hat viele Gesichter.</p>
      </a>
      <a href="#video-4" class="list-group-item">
        <h4 class="list-group-item-heading">Boring video</h4>
        <p class="list-group-item-text">Ein Mann bittet ein Mädchen um Verzeihung.</p>
      </a>
      <a href="#video-5" class="list-group-item">
        <h4 class="list-group-item-heading">Video about videos</h4>
        <p class="list-group-item-text">Ein Mädchen spielt mit dem Feuer. Ein Mädchen weiß nicht, was gut für es ist. Ein Mädchen sollte weg rennen.</p>
      </a>
      <a href="#video-6" class="list-group-item">
        <h4 class="list-group-item-heading">Videooooooo</h4>
        <p class="list-group-item-text">Ein Mann tippt Wörter auf einer Tastatur.</p>
      </a>
    </div>

    <div class="info-video" id="video-1">
      <h3>Some video</h3>
      <p>
        Sehen sie hier, wie ein Mann lacht.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/RvOnXh3NN9w" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-2">
      <h3>Another video</h3>
      <p>
        Sehen sie hier, wie ein Mann weint.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/ao8L-0nSYzg" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-3">
      <h3>Awesome video</h3>
      <p>
        Ein Mann hat viele Gesichter.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/sNhhvQGsMEc" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-4">
      <h3>Boring video</h3>
      <p>
        Ein Mann bittet ein Mädchen um Verzeihung.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/QOCaacO8wus" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-5">
      <h3>Video about videos</h3>
      <p>
        Ein Mädchen spielt mit dem Feuer. Ein Mädchen weiß nicht, was gut für es ist. Ein Mädchen sollte weg rennen.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/4_aOIA-vyBo" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

    <div class="info-video" id="video-6">
      <h3>Videooooooo</h3>
      <p>
        Ein Mann tippt Wörter auf einer Tastatur.
      </p>
      <div class="videoWrapper">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/V9_PjdU3Mpo" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>

  </div>
@endsection
