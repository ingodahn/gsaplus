<?php
  $visible = $isPatient && $EntryInfo['status'] == 'E050';
  $editable = $visible;
?>
@if ($visible)
<h3>Bewertung der Rückmeldung des Therapeuten</h3>

  <div class="bs-callout bs-callout-warning">
    <p>Sie werden nachdrücklich aufgefordert, diese Felder auszufüllen.</p>
  </div>

  <div class="container-fluid">

    <div class="form-group">
      <?php $checked = $EntryInfo['comment_reply']['satisfied']; ?>
      <div class="row">
        <div class="col-md-7">
          <label for="comment_reply_satisfied">Wie zufrieden waren Sie mit der Rückmeldung des Onlinetherapeuten?</label>
        </div>
        @for($j = 0; $j < 4; $j++)
          <div class="col-md-1">
            <label class="radio-inline">
              <input type="radio" name="comment_reply_satisfied" id="comment_reply_satisfied" value="{{$j}}" {{$checked == $j ? "checked" : ""}}> {{$j}}
            </label>
          </div>
        @endfor
      </div>
    </div>

    <div class="form-group">
      <?php $checked = $EntryInfo['comment_reply']['helpful']; ?>
      <div class="row">
        <div class="col-md-7">
          <label for="comment_reply_helpful">Wie hilfreich waren die Rückmeldungen des Onlinetherapeuten?</label>
        </div>
        @for($j = 0; $j < 4; $j++)
          <div class="col-md-1">
            <label class="radio-inline">
              <input type="radio" name="comment_reply_helpful" id="comment_reply_helpful" value="{{$j}}" {{$checked == $j ? "checked" : ""}}> {{$j}}
            </label>
          </div>
        @endfor
      </div>
    </div>

  </div>
@endif
