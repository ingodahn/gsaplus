<!-- email including a password reset link -->
<html>
    <body>
        <p>Sehr geehrter Nutzer,</p>
        <p>Sie können Ihr Passwort unter folgender Adresse ändern:</p>
        <p><a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a></p>
        <p>Viele Grüße,<br/>
            Ihr Team GSA-Online Plus</p>
    </body>
</html>