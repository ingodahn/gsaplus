<hr>
<h3>Persönliche Informationen</h3>
@if($isPatient)
  <form data-parsley-validate role="form" action="/patient/{{$Patient['name']}}/personal_information" method="post">
    {{ csrf_field() }}
    <div class="form-group">
      <label for="personal_information" class="control-label">nur für Therapeuten sichtbar</label>
      <textarea name="personal_information" rows="5" class="form-control js-auto-size" placeholder="Hier können Sie Informationen eintragen, welche nur für Ihren Therapeuten sichtbar sind.">{{ $Patient['personalInformation'] }}</textarea>
    </div>
    <p>
      <div class="form-group">
        <button type="submit" class="btn">Informationen ändern</button>
      </div>
    </p>
  </form>
@else
  <p>
    {!! nl2br(e($Patient['personalInformation'])) !!}
  </p>
@endif
