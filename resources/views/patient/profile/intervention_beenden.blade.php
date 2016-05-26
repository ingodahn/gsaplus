@if($isTherapist)
  <hr>
  <h3>Intervention beenden</h3>
  <label for="end_intervention" class="control-label">Achtung: Danach kann der Patient keine neuen Eingaben mehr machen!</label>
  <p>
    <a href="/patient/{{$Patient['name']}}/cancel_intervention" class="btn btn-danger" onclick="return confirm('Wollen Sie die Zusammenarbeit mit diesem Patienten wirklich beenden?');">Intervention beenden</a>
  </p>
@endif
