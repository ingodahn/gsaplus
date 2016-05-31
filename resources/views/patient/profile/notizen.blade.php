@if ($isTherapist)
  <hr>
  <h3>Notizen der Therapeuten <a href="javascript:void(0)" data-toggle="popover" data-placement="right" data-html="true" data-trigger="focus" title="Auswahlhilfe und Editor für die Schreibimpuls-Vorlagen" data-content="
    Hier können Sie als Therapeut Notizen zu dem jeweiligen Patienten hinterlassen, z.B. um den vorliegenden zentralen Beziehungskonflikt intern zu erörtern.<br><br>
    Diese Notizen sind für alle Therapeuten sichtbar, nicht aber für Patienten.
    "><i class="fa fa-question-circle"></i></a></h3>
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/notes" method="post">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="notes" class="control-label">für Patienten nicht sichtbar</label>
      <textarea name="notes" rows="5" class="form-control js-auto-size" placeholder="Hier können Sie Notizen eintragen, welche nur für Therapeuten sichtbar sind. Patienten werden diese Notizen nicht sehen können">{{ $Patient['notesOfTherapist'] }}</textarea>
    </div>
    <p>
      <div class="form-group">
        <button type="submit" class="btn">Notizen ändern</button>
      </div>
    </p>
  </form>
@endif
