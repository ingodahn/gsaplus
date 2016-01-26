@extends('layouts.master')
@section('title', 'Registrierung')

@section('content')
  <div class="container">

    <h2>Slots</h2>
    <form class="" action="/set_slots" method="post">
      <div class="row">
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">So</span>
            <input type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Mo</span>
            <input type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Di</span>
            <input type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Mi</span>
            <input type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Do</span>
            <input type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary">Setzen</button>
        </div>
      </div>
    </form>

  </div>
@endsection
