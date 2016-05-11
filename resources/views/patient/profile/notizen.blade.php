@if ($isTherapist)
  <hr>
  <h3>Notizen der Therapeuten</h3>
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/notes" method="post">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="notes" class="control-label">für Patienten nicht sichtbar</label>
      <textarea name="notes" rows="5" class="form-control js-auto-size" placeholder="Hat eine zu krasse Meinung zu Earl Grey.">{{ $Patient['notesOfTherapist'] }}</textarea>
    </div>
    <p>
      <div class="form-group">
        <button type="submit" class="btn">Notizen ändern</button>
      </div>
    </p>
  </form>
@endif
