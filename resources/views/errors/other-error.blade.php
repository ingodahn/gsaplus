<!DOCTYPE html>
<html>
    <head>
        <title>Fehler</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: bold;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 60px;
                margin-bottom: 20px;
            }

            .description {
                font-size: 36px;
                margin-bottom: 20px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <span class="title">Leider ist ein interner Fehler aufgetreten</span>
                <span class="title">
                    @if($statusCode)
                        &nbsp;({{ $statusCode }}).
                    @else
                        .
                    @endif
                </span>
                <div class="title">Zurück zu <a href="/">GSA-Online</a></div>.
                <div class="description">Bitte wenden Sie sich an das <a href="/ContactTeam">Team</a> falls der Fehler
                    weiterhin auftreten sollte.</div>
            </div>
        </div>
    </body>
</html>
