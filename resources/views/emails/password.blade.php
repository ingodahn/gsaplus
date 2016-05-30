<!-- email including a password reset link -->
<html>
    <body>
        <p>Sehr geehrter Nutzer,</p>
        <p>um Ihr Passwort zu ändern, nutzen Sie bitte den folgenden Link:</p>
        <p><a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a></p>
        <p>Viele Grüße,<br/>
            Ihr Team von GSA-Online plus</p>
    </body>
</html>