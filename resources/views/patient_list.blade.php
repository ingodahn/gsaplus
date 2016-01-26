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
            <input name="So_slots" type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Mo</span>
            <input name="Mo_slots"  type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Di</span>
            <input name="Di_slots"  type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Mi</span>
            <input name="Mi_slots"  type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <div class="input-group">
            <span class="input-group-addon">Do</span>
            <input name="Do_slots"  type="number" class="form-control" value="1">
          </div>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary">Setzen</button>
        </div>
      </div>
    </form>

  </div>
@endsection
