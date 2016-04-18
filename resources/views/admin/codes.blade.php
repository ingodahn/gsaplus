@extends('layouts.master')
@section('title', 'Codes')

@section('content')
  <div class="container">
    <h2>Codes</h2>

    <div class="row">
      <?php $i = 0; ?>
      @foreach($codes as $code => $name)
        @if($i % 12 == 0)
          </div>
          <div class="row">
        @endif
        <?php $i++; ?>
        <div class="col-xs-2 col-sm-1 {{$name ? "used" : "unused"}}">
          @if($name)
            <a href="/profile/{{$name}}">
              <code>{{$code}}</code>
            </a>
          @else
            <code>{{$code}}</code>
          @endif
        </div>
      @endforeach
    </div>
  </div>
@endsection
