@if ($Role=='therapist')
  <div class="form-group">
    <label for="notesOfTherapist">Notizen der Therapeuten</label>
    <textarea class="form-control js-auto-size" name="notesOfTherapist">{{$PatientInfo['notesOfTherapist']}}</textarea>
  </div>
@endif
