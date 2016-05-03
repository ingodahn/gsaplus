@if($isTherapist)
  <hr>
  <h3>Therapeut</h3>
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/therapist" method="post" }}>
    {{ csrf_field() }}
    <div class="form-group">
      <label for="therapist" class="control-label">Patient wird betreut von</label>
      <select name="therapist" class="form-control" required>
        <option>{{ $Patient['therapist'] }}</option>
        @foreach(array_diff($Patient['listOfTherapists'], [$Patient['therapist']]) as $therapist)
          <option>{{$therapist}}</option>
        @endforeach
      </select>
    </div>
    <p>
      <div class="form-group">
        <button type="submit" class="btn">Therapeuten setzen</button>
      </div>
    </p>
  </form>
@endif
