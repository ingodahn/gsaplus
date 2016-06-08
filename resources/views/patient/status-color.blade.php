<?php
$displayState = "default";
switch($statusCode) {
  case "E010": $displayState = "default"; break; // Schreibimpuls nicht definiert
  case "E015": $displayState = "default"; break; // Schreibimpuls definiert
  case "E020": $displayState = "primary"; break; // Schreibimpuls gegeben
  case "E030": $displayState = "primary"; break; // Tagebucheintrag bearbeitet
  case "E040": $displayState = "primary"; break; // Tagebucheintrag abgeschickt
  case "E050": $displayState = "primary"; break; // Tagebucheintrag mit Rückmeldung versehen
  case "E060": $displayState = "success"; break; // Rückmeldung bewertet
  case "E070": $displayState = "primary"; break; // Tagebucheintrag überfällig
  default: $displayState = "default"; break;
}
echo($displayState);
?>
