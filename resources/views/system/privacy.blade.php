@extends('layouts.master')
@section('title', 'Datenschutz')
@section('additional-head')
  <script language="javascript" type="text/javascript">
    function resizeIframe(obj) {
      obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }
  </script>
@endsection

@section('content')
    <div class="container">

      <h3>Datenschutzerklärung</h3>
      <p>
        Vielen Dank für Ihren Besuch auf <a href="www.online-nachsorge.de">www.online-nachsorge.de</a>. Wir nehmen den Schutz Ihrer Privatsphäre bei der Erhebung, Verarbeitung und Nutzung Ihrer personenbezogenen Daten gemäß den gesetzlichen Bestimmungen ernst und möchten, dass Sie sich auf unserer Internetplattform nicht nur mit dem Thema 'berufsbezogene Belastungen' auseinandersetzten, sondern dass Sie sich dabei auch sicher fühlen.
      </p>
      <p>
        Diese Datenschutzerklärung gibt Hinweise zu personenbezogenen Daten beim Aufrufen dieser Internetplattform, zu Cookies, zu Links und zu E-Mails.
      </p>

      <h3>Begriffe und Grundsätze</h3>
      <p>
        Beim Datenschutz geht es immer um personenbezogene Daten, wie zum Beispiel Name, Anschrift, E-Mail-Adressen und sonstige Daten zu einer Person.
      </p>
      <p>
        Gemäß dem Grundsatz der Datenvermeidung und Datensparsamkeit werden von uns personenbezogene Daten nur erhoben, wenn diese erforderlich sind und Sie uns diese freiwillig angeben.
      </p>
      <p>
        Jeder Zugriff auf das Internet-Angebot der Universitätsmedizin der Johannes Gutenberg Universität, Mainz,  wird protokolliert – dabei werden automatisiert bestimmte Verbindungsdaten gespeichert. Diese Protokolldatei ist notwendig, um einen ordnungsgemäßen technischen Betrieb garantieren zu können. Die gespeicherten Daten werden nur für statistische Zwecke ausgewertet und sobald sie nicht mehr benötigt werden, überschrieben; spätestens nach drei Monaten.
      </p>
      <p>
        Die Auswertung erzeugt aggregierte Informationen wie z.B. Anzahl der Besucher/pro Monat oder Anzahl der aufgerufenen Seiten u.a. Auf diese Weise zusammengefasste Daten sind anonym, sie  können bestimmten Personen nicht zugeordnet werden. Eine Zusammenführung von Verbindungsdaten mit anderen Datenquellen wird von uns nicht vorgenommen.
      </p>
      <p>
        Zur Benutzung von <a href="www.online-nachsorge.de">www.online-nachsorge.de</a> müssen Sie keine personenbezogenen Daten angeben. Die Anmeldung erfolgt mit dem Ihnen zugewiesenen Zugangscode und einem frei wählbaren Benutzernamen. Bei der Wahl des Benutzernamens sollten Sie ausdrücklich auf personenbezogene Bestandteile wie z.B. Vorname, Nachname, Geburtsdatum verzichten. Die Teilnahme am Online-Nachsorgeprogramm ist auf der Internetplattform durch die Wahl eines individuellen Benutzernamens anonymisiert. Eine Zuordnung zu personenbezogenen Daten ist online nicht möglich und unterliegt den generellen Datenschutzbestimmungen der Studie, ist also nur im Notfall durch den Datentreuhänder möglich. Alle studienbezogenen Daten werden pseudonymisiert (d.h. kodiert ohne Angabe von Namen, Anschrift, Initialen oder Ähnliches) erhoben, auf Datenträgern gespeichert  und durch die Klinik und Poliklinik für Psychosomatische Medizin und Psychotherapie der Universitätsmedizin Mainz ausgewertet. Die Weitergabe an Dritte einschließlich Publikation erfolgt ausschließlich in anonymisierter Form, d.h. die Daten können keiner einzelnen Person zugeordnet werden.
      </p>

      <h3>Erhebung personenbezogener Daten</h3>
      <p>
        Nach der Anmeldung mit Benutzername und Passwort erfolgt die Datenübertragung verschlüsselt unter Verwendung einer 128 Bit SSL-Verschlüsselung (Secure Socket Layers), einem international anerkannten Sicherheitsstandard.
      </p>
      <p>
        Wir weisen Sie darauf hin, dass die Eingabe Ihrer persönlichen Daten grundsätzlich freiwillig erfolgt, Sie jedoch keine persönlichen Daten im Forum angeben sollten (z.B. Name oder Geburtsdatum). Von Seiten des Studienteams werden wir Sie nie auffordern, Ihren Namen und Ihr Geburtsdatum anzugeben. Die Angabe der E-Mail Adresse ist freiwillig und erleichtert uns den Kontakt zu Ihnen. Die E-Mail ist für andere Teilnehmer nicht einsehbar und wird verschlüsselt in der Datenbank gespeichert. Die Daten werden von der Universitätsmedizin Mainz nicht an Dritte weitergegeben; weder zu kommerziellen noch zu nicht kommerziellen Zwecken.
      </p>
      <p>
        Wenn Sie mit uns per E-Mail in Kontakt treten möchten, weisen wir Sie darauf hin, dass die Vertraulichkeit der übermittelten Informationen auf dem Übertragungsweg durch das Internet nicht gewährleistet ist. Der Inhalt von E-Mails kann von Dritten eingesehen werden. Wir empfehlen Ihnen daher, uns telefonisch zu kontaktieren, wenn Sie uns vertrauliche Informationen zukommen lassen.
      </p>

      <h3>Erfassung statistischer Daten</h3>
      <iframe style="border: 0; width: 100%;" scrolling="no" onload="resizeIframe(this)" src="{{config('piwik.protocol')}}://{{config('piwik.host')}}{{config('piwik.path')}}/index.php?module=CoreAdminHome&action=optOut&idsite=1&language=de"></iframe>

      <h3>Cookies</h3>
      <p>
        Die Internetseiten verwenden teilweise sogenannte Cookies. Cookies richten auf Ihrem Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und die Ihr Browser speichert.
      </p>
      <p>
        Die meisten der von uns verwendeten Cookies sind sogenannte „Session-Cookies“. Sie werden nach Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert, bis Sie diese löschen. Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch wiederzuerkennen.
      </p>
      <p>
        Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browsers aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.
      </p>

    </div>
@endsection
