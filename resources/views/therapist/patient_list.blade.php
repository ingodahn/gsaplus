@extends('layouts.master')
@section('title', 'Registrierung')

@section('content')
  <div class="container">

    <h2>Slots</h2>
    <p>
      <form class="" action="/SetSlots" method="post">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Mo</span>
              <input name="Mo_slots"  type="number" class="form-control" value="{{$Slots['Montag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Di</span>
              <input name="Di_slots"  type="number" class="form-control" value="{{$Slots['Dienstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Mi</span>
              <input name="Mi_slots"  type="number" class="form-control" value="{{$Slots['Mittwoch']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">Do</span>
              <input name="Do_slots"  type="number" class="form-control" value="{{$Slots['Donnerstag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group">
              <span class="input-group-addon">So</span>
              <input name="So_slots" type="number" class="form-control" value="{{$Slots['Sonntag']}}">
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Setzen</button>
          </div>
        </div>
      </form>
    </p>
	<p>Hier kommt die Patientenliste hin</p>
	{!! $PatientList !!}
    <p>
      <a href="/Logout" class="btn btn-warning">Logout</a>
    </p>

  </div>
@endsection
