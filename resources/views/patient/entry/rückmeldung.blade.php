<?php
  $visible = $isPatient && $EntryInfo['status']>= 'E050' || $isTherapist;
  $editable = $isPatient && $EntryInfo['status'] == 'E040';
?>
@if($visible)
  <h3>Rückmeldung des Therapeuten</h3>
  <div class="form-group">
    <label for="comment">Rückmeldung des Therapeuten auf Ihren Tagebucheintrag</label>
    <textarea class="form-control js-auto-size" id="comment" placeholder="" {{$editable ? "" : "disabled"}}>{{$EntryInfo['comment']}}</textarea>
  </div>
@endif
