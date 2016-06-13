<?php
$displayState = "default";
switch($statusCode) {
  case "E010": $displayState = "undefined"; break; // Schreibimpuls nicht definiert
  case "E015": $displayState = "current"; break; // Schreibimpuls definiert
  case "E020": $displayState = "current"; break; // Schreibimpuls liegt für Sie bereit
  case "E030": $displayState = "current"; break; // Tagebucheintrag in Bearbeitung und zwischengespeichert
  case "E040": $displayState = "current"; break; // Tagebucheintrag abgeschickt
  case "E050": $displayState = "current"; break; // Ihr Onlinetherapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung.
  case "E060": $displayState = "done"; break; // Tagebucheintrag und Rückmeldung abgeschlossen
  case "E070": $displayState = "overdue"; break; // Kein Tagebucheintrag vorhanden
  case "E100": $displayState = "undefined"; break; // Kein Tagebucheintrag vorhanden
  default: $displayState = "undefined"; break;
}
echo($displayState);
?>
