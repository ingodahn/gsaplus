<hr>
<h3>Schreibtag</h3>
<p>
  Ihr aktueller Schreibtag ist <strong>{{ $Patient['assignmentDay'] }}</strong>. Es
  @if($Patient['assignmentDayChangesLeft'] == 1)
    verbleibt
  @else
    verbleiben
  @endif
  @if($Patient['assignmentDayChangesLeft'] > 0)
    noch <strong>{{$Patient['assignmentDayChangesLeft']}}</strong>
    @if($Patient['assignmentDayChangesLeft'] == 1)
      Änderung Ihres Schreibtages.
    @else
      Änderungen Ihres Schreibtages.
    @endif
  @else
    <strong>keine</strong> Änderungen mehr.
  @endif
</p>
@if($isPatient && $Patient['assignmentDayChangesLeft'] > 0)
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/day_of_week" method="post">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="day_of_week" class="control-label">Wochentag</label>
      <a href="javascript:void(0)" tabindex="0" data-toggle="popover" data-trigger="focus" title="Warum sind nicht alle Wochentage wählbar?" data-content="Wir möchten, dass Sie nach dem Schreiben Ihres Tagebuchs möglichst innerhalb von 24 h eine Rückmeldung Ihres Online-Therapeuten erhalten. Da wir dies jedoch nur von Montag bis Freitag mit begrenzten Kapazitäten zusagen können, sind nicht alle Tage als Schreibtage wählbar.">
        <i class="fa fa-question-circle"></i>
      </a>
      <select name="day_of_week" class="form-control" required>
        <option>{{ $Patient['assignmentDay'] }}</option>
        @foreach(array_diff($Patient['availableDays'], [$Patient['assignmentDay']]) as $day)
          <option>{{ $day }}</option>
        @endforeach
      </select>
    </div>
    <p>
      <div class="form-group">
        <button type="submit" class="btn">Schreibtag setzen</button>
      </div>
    </p>
  </form>
@endif
