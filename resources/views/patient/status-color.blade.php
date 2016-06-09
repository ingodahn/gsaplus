<?php
$displayState = "default";
switch($statusCode) {
  case "E010": $displayState = "undefined"; break; // Schreibimpuls nicht definiert
  case "E015": $displayState = "current"; break; // Schreibimpuls definiert
  case "E020": $displayState = "current"; break; // Schreibimpuls gegeben
  case "E030": $displayState = "current"; break; // Tagebucheintrag bearbeitet
  case "E040": $displayState = "current"; break; // Tagebucheintrag abgeschickt
  case "E050": $displayState = "current"; break; // Tagebucheintrag mit Rückmeldung versehen
  case "E060": $displayState = "done"; break; // Rückmeldung bewertet
  case "E070": $displayState = "overdue"; break; // Tagebucheintrag überfällig
  case "E100": $displayState = "undefined"; break; // Tagebucheintrag überfällig
  default: $displayState = "undefined"; break;
}
echo($displayState);
?>
