<?php
  $visible = $isPatient && $EntryInfo['status']>= 'E050' || $isTherapist;
  $editable = $isTherapist && $EntryInfo['status'] == 'E040';
?>
@if($visible)
  <h3><i class="fa fa-commenting" aria-hidden="true"></i> Rückmeldung des Therapeuten</h3>
  <div class="form-group">
    <label for="comment">Rückmeldung des Therapeuten auf Ihren Tagebucheintrag</label>
    <textarea class="form-control js-auto-size" id="comment" name="comment" placeholder="" {{$editable ? "" : "disabled"}}>{{$EntryInfo['comment']}}</textarea>
  </div>
@endif
