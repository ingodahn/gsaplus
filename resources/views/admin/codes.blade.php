@extends('layouts.master')
@section('title', 'Codes')

@section('additional-head')
  <style>
    ul.nav {
      display: none;
    }
  </style>
@endsection

@section('content')
  <div class="container">
    <h2>Codes</h2>

    <p>
      <span class="used"><code>Registriert</code></span>

      <span class="unused"><code>Nicht registriert</code></span>
    </p>
    <br>

    <div class="row">
      <?php $i = 0; ?>
      @foreach($codes as $code => $name)
        @if($i % 4 == 0)
          </div>
          <div class="row" style="margin-bottom:10px">
        @endif
        <?php $i++; ?>
        <div class="col-xs-3 col-sm-3 {{$name ? "used" : "unused"}}">
          @if($name)
            <a href="/Profile/{{$name}}">
              <code>{{$code}}</code>
            </a>
          @else
            <code>{{$code}}</code>
          @endif
        </div>
      @endforeach
    </div>

    <br/>

    <div class="pull-right">
      <a class="btn btn-primary" onclick="window.close()"><i class="fa fa-close" aria-hidden="true"></i> &nbsp;Schließen</a>
    </div>
  </div>
@endsection
