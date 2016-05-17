@if($isPatient)
  <hr>
  <h3>Piwik OptOut</h3>
  <p>
    Hier können Sie festlegen, ob sie an der Erfassung der Nutzungsdaten teilnehmen möchten.
  </p>
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/piwikOptOut" method="post">
    {{ csrf_field() }}

    <div class="form-group">
      @if($piwikOptOut)
        <button type="submit" class="btn" name="piwikOptOut" value="false">Datenerfassung aktivieren</button>
      @else
        <button type="submit" class="btn" name="piwikOptOut" value="true">Datenerfassung deaktivieren</button>
      @endif
    </div>
  </form>
@endif
