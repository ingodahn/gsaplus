<h3>Schreibimpuls</h3>
@if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
  <p>{!! nl2br(e($EntryInfo['problem'])) !!}</p>
@elseif($isTherapist && $EntryInfo['status'] < 'E020')
  <p>
    <div class="form-group">
      <label for="problem">Schreibimpuls bearbeiten</label>
      <textarea class="form-control js-auto-size" name="problem">{{$EntryInfo['problem']}}</textarea>
    </div>
  </p>
@endif
