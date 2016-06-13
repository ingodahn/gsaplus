@extends('layouts.master')
@section('title', 'Administratoren Backend')

@section('additional-head')
    <script src="/js/zxcvbn.js" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            evaluate();
            $("#password").keyup(function (event) {
                evaluate();
            });

            function evaluate() {
                var password = $("#password").val();
                if (password) {
                    var result = zxcvbn(password);
                    switch (result.score) {
                        case 0:
                            updateText("Sehr schwach"); break;
                        case 1:
                            updateText("Schwach"); break;
                        case 2:
                            updateText("Ok"); break;
                        case 3:
                            updateText("Stark"); break;
                        case 4:
                            updateText("Sehr stark"); break;
                    }
                } else {
                    updateText("-");
                }

                function updateText(text) {
                    $("#strength-addon").text(text);
                }
            }
        });
    </script>
    <style>
        td {
            vertical-align:middle !important;
        }

        th {
            white-space : nowrap;
        }

        hr {
            border-top: 1px dashed #8c8b8b;
        }
    </style>
@endsection

@section('content')

    {{-- Add smooth scrolling --}}
    <script type="text/javascript">
        $(function() {
            $('a[href*="#"]:not([href="#"])').click(function() {
                if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top
                        }, 300);
                        return false;
                    }
                }
            });
        });
    </script>

    <?php use App\Models\UserRole; ?>

    <div class="container" id="top">

        <div class="row">
            <h3>Administratoren Backend</h3>
            <p>Auf dieser Seite können Sie die im System vorhandenen</p>
            <ul>
                <li>
                    <a href="#therapists">Therapeuten</a>
                </li>
                <li>
                    <a href="#patients">Patienten</a>, und
                </li>
                <li>
                    <a href="#admins">Administratoren</a>
                </li>
            </ul>
            <p>einsehen und <a href="#add_therapist">neue Therapeuten anlegen</a>.</p>
            <p>Im Notfall können Sie <a href="#notifications">anstehende Benachrichtigungen versenden</a> -
                z.B. wenn der Mail-Server ausgefallen ist und die Benachrichtigungen nicht versendet werden konnten.</p>
            <p>Am Ende der Seite können Sie alle im System gespeicherten <a href="#export_codes">Codes exportieren</a>
                (alternativ können Sie die <a href="/AdminCodes" target="_blank">Codes im Browser einsehen</a>).
            </p>
        </div>

        <div class="row">
            <hr />
        </div>

        <div class="row" id="add_therapist">
            <h3>Therapeut anlegen</h3>

            {{-- All active form content must stay in this form for frontend and backend processing --}}
            <form id="registration-form" data-parsley-validate role="form" action="/admin/therapists/new" method="post">
                {{ csrf_field() }}

                <p>Bitte wählen Sie einen Benutzernamen (nur Buchstaben, Zahlen, <code>-</code>, <code>_</code> und <code>.</code>)und ein Passwort und geben Sie eine gültige E-Mail Adresse ein:</p>

                <div class="form-group">
                    <label for="name" class="control-label">Benutzername</label>
                    <input name="name" type="text" class="form-control" placeholder="mrhyde63" required pattern="^[a-zA-Z0-9\.\-_]+$">
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="password" class="control-label">Passwort</label>
                        <div class="input-group">
                            <input name="password" id="password" type="password" autocomplete="off" class="form-control width-100" placeholder="hunter2 (mindestens 6 Zeichen)" required minlength="6" aria-describedby="strength-addon">
                            <span class="input-group-addon" id="strength-addon"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="control-label">Passwort wiederholen</label>
                        <input type="password" autocomplete="off" class="form-control width-100" placeholder="hunter2" required minlength="6" data-parsley-equalto="#password">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="email" class="control-label">E-Mail Adresse</label>
                        <input name="email" id="email" type="email" class="form-control width-100" placeholder="w.meyer@web.de" required>
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="control-label">E-Mail wiederholen</label>
                        <input type="email" placeholder="w.meyer@web.de" class="form-control width-100" required data-parsley-equalto="#email">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 10px">
                    <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-save" aria-hidden="true"></span> &nbsp;Therapeut anlegen</button>
                </div>
            </form>
        </div>

        @foreach($infos as $role => $users)
            <div class="row">
                <hr />
            </div>

            <div class="row">
                <h4 id="{{ $role }}s">
                    @if($role == UserRole::PATIENT)
                        Patienten
                    @elseif($role == UserRole::THERAPIST)
                        Therapeuten
                    @elseif($role == UserRole::ADMIN)
                        Administratoren
                    @endif
                </h4>

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-Mail</th>
                        @if($role == UserRole::PATIENT)
                            <th>Status</th>
                            <th>Code</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="/admin/dump-info/{{$user['name'] }}" target="_blank">
                                    {{ $user['name'] }}
                                </a>
                            </td>
                            <td>
                                {{ $user['email'] }}
                            </td>
                            @if($role == UserRole::PATIENT)
                                <td>
                                    <em>{{ $user['patientStatus']  }}</em>
                                    <a href="javascript:void(0)" data-toggle="popover" class="btn" data-placement="right"
                                       data-html="true" data-trigger="focus" title="Es gibt folgende Patienten-Status (P)"
                                       data-content="<ul>
                                        <li>Registriert (<strong>P020</strong>)</li>
                                        <li>Entlassungsdatum erfasst (<strong>P025</strong>)</li>
                                        <li>Entlassen (<strong>P028</strong>)</li>
                                        <li>Schreibimpuls erhalten (<strong>P030</strong>)</li>
                                        <li>Tagebucheintrag in Bearbeitung und zwischengespeichert (<strong>P040</strong>)</li>
                                        <li>Tagebucheintrag gemahnt (<strong>P045</strong>)</li>
                                        <li>Tagebucheintrag abgeschickt (<strong>P050</strong>)</li>
                                        <li>Ihr Online-Therapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung. (<strong>P060</strong>)</li>
                                        <li>Tagebucheintrag und Rückmeldung abgeschlossen (<strong>P065</strong>)</li>
                                        <li>Mitarbeit beendet (<strong>P130</strong>)</li>
                                        <li>Interventionszeit beendet (<strong>P140</strong>)</li>
                                        </ul>">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                </td>
                                <td>
                                    <em>{{ $user['code'] }}</em>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p class="text-right">
                    <small>
                        <a href="#top">Zum Seitenanfang
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        </a>
                    </small>
                </p>
            </div>
        @endforeach

        <div class="row">
            <hr />
        </div>

        <div class="row">
            <h4 id="notifications">Benachrichtigungen</h4>
            <p>Versenden Sie anstehende Benachrichtigungen. Patienten werden (in der Regel am Folgetag) benachrichtigt falls</p>
                <ul>
                    <li>Sie sich <em>neu registriert</em> haben</li>
                    <li>Sie aus der Klinik <em>entlassen</em> wurden</li>
                    <li>Sie den ersten bzw. einen Folge-<em>Schreibimpuls</em>
                        <ul>
                            <li><em>erhalten</em> oder</li>
                            <li>2 bzw. 5 Tage lang <em>nicht beantwortet</em> haben</li>
                        </ul>
                    <li>Sie die <em>Intervention erfolgreich abgeschlossen</em> haben oder</li>
                    <li>der Therapeut den <em>Abbruch der Intervention</em> bestätigt hat.</li>
                </ul>
            </p>
            <p><strong>Achtung:</strong> Benachrichtigungen werden nur einmal verschickt.</p>
            <form method="POST" action="/admin/send-notifications/all"
                  class="pull-right floating-btn-form">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary pull-right">Benachrichtigungen versenden</button>
            </form>
            <p class="text-right" style="clear: both; padding-top:20px">
                <small>
                    <a href="#top">Zum Seitenanfang
                        <span class="glyphicon glyphicon-arrow-up"></span>
                    </a>
                </small>
            </p>
        </div>

        <div class="row">
            <hr />
        </div>

        <div class="row">
            <h4 id="export_codes">Code Export</h4>
            <p>Speichern Sie eine Liste mit allen Codes. Die Codes sind nach Klinik sortiert. Wählen Sie in Excel
                die entsprechende Seite indem Sie unten auf den entsprechenden Reiter klicken - z.B.
                <em>Codes für Klinik A</em>.</p>
            <form method="GET" action="/admin/codes-as-csv"
                  class="pull-right floating-btn-form">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-save" aria-hidden="true"></span> &nbsp;Codeliste speichern</button>
            </form>
            <p class="text-right" style="clear: both; padding-top:20px">
                <small>
                    <a href="#top">Zum Seitenanfang
                        <span class="glyphicon glyphicon-arrow-up"></span>
                    </a>
                </small>
            </p>
        </div>

    </div>
@endsection
