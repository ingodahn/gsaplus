<!-- Mail inviting to participation in SoSciSurvey -->
<html>
<body>
<p>
    Sehr geehrter Teilnehmer {!! $PatientName !!},
</p>
<p>
    Bald geht es los - im Rahmen der Online-Nachsorge GSA-Online plus werden Sie am
    {!! $AssignmentDay !!}, {!! $NextWritingDate !!}, Ihren ersten Schreibimpuls
    erhalten.
</p>
<p>
    Wir möchten wir Sie bitten, zu Beginn und zum Ende der Nachsorge einen Online-Fragenbogen zu beantworten.
</p>
<p>
    Um unsere Online-Nachsorge künftig möglichst zielgerichtet interessierten Rehabilitanden zu empfehlen,
    ist es für uns von Interesse, wie sich Ihre Rehabilitationsbehandlung auf Ihre berufliche Situation und
    Ihre Gesundheit ausgewirkt hat und wie häufig und gerne Sie das Internet nutzen.
</p>
<p>
    Selbstverständlich werden Ihre Angaben vertraulich und in pseudonymisierter Form ausgewertet.
</p>
<p>
    Bitte folgen Sie diesem Link, um zum Fragebogen zu gelangen: <a href="https://www.soscisurvey.de/GSAonlineplus/?q=GSA_T1&s={!! str_replace("-","",$PatientCode) !!}">Fragebogen ausfüllen</a>
</p>
<p>
    <strong>Herzlichen Dank für Ihre Mitarbeit! </strong>
</p>
<p>
    Viele Grüße,
</p>
<p>
    Ihr Team von GSA-Online plus
</p>
</body>
</html>
