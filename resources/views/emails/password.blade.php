<!-- email including a password reset link -->
<html>
    <body>
        <p>Sehr geehrter Nutzer,</p>
        <p>mitdiesem Link können Sie Ihr <a href="{{ url('password/reset/'.$token) }}">Passwort ändern</a>.</p>
        <p></p>
        <p>Viele Grüße,<br/>
            Ihr Team von GSA-Online plus</p>
    </body>
</html>