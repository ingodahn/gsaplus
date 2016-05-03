<?php
  $visible = $isPatient && $EntryInfo['status'] < 'E040' || $isTherapist;
  $editable = $isPatient && $EntryInfo['status'] < 'E040';
?>
@if($visible)
  <h3>Fragen zum Befinden</h3>
  <p>
    <strong>Wir wollen herausfinden, wie gut oder schlecht Ihre Gesundheit <em>heute</em> ist:</strong>
  </p>
  <ul>
    <li>10 ist die beste Gesundheit, die Sie sich vorstellen können.</li>
    <li>0 ist die schlechteste Gesundheit, die Sie sich vorstellen können.</li>
    <li>Bitte wählen Sie den Wert, der Ihre Gesundheit <strong><em>heute</em></strong> am besten beschreibt.</li>
  </ul>

  <div class="container-fluid big-radios">
    <div class="form-group">
      <div class="row">
        @for($i=0; $i <= 10; $i++)
          <div class="col-md-1">
            <label class="radio-inline big-radio">
              <input type="radio" name="health" value="{{$i}}" {{$EntryInfo['survey']['health'] == $i ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$i}}
            </label>
          </div>
        @endfor
      </div>
    </div>
  </div>
  <br>
  <p>
    <strong>
      Derzeitige Arbeitsfähigkeit im Vergleich zu der besten, je erreichten Arbeitsfähigkeit:
    </strong>
  </p>

  <p>
    Wenn Sie Ihre beste, je erreichte Arbeitsfähigkeit mit 10 Punkten bewerten: Wie viele Punkte würden Sie dann für Ihre derzeitige Arbeitsfähigkeit geben? (0 bedeutet, dass Sie derzeit arbeitsunfähig sind):
  </p>
  <div class="container-fluid big-radios">
    <div class="form-group">
      <div class="row">
        @for($i=0; $i <= 10; $i++)
          <div class="col-md-1">
            <label class="radio-inline big-radio">
              <input type="radio" name="wai" value="{{$i}}" {{$EntryInfo['survey']['wai'] == $i ? "checked" : ""}} {{$editable ? "" : "disabled"}}> {{$i}}
            </label>
          </div>
        @endfor
      </div>
    </div>
  </div>
@endif
