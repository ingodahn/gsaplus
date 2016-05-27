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
                    <a href="/AdminCodes" target="_blank">Codes</a>
                </li>
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
            <p>Im Notfall können Sie <a href="#reminders">anstehende Benachrichtigungen versenden</a> -
                z.B. wenn der Mail-Server ausgefallen ist und die Benachrichtigungen nicht versendet werden konnten.</p>
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
                                    <a href="javascript:void(0)" data-toggle="popover" data-placement="right"
                                       data-html="true" data-trigger="focus" title="Es gibt folgende Patienten-Status (P)"
                                       data-content="<ul>
                                        <li>Registriert (<strong>P020</strong>)</li>
                                        <li>Entlassungsdatum erfasst (<strong>P025</strong>)</li>
                                        <li>Schreibimpuls erhalten (<strong>P030</strong>)</li>
                                        <li>Tagebucheintrag bearbeitet (<strong>P040</strong>)</li>
                                        <li>Tagebucheintrag gemahnt (<strong>P045</strong>)</li>
                                        <li>Tagebucheintrag abgeschickt (<strong>P050</strong>)</li>
                                        <li>Tagebucheintrag mit Rückmeldung versehen (<strong>P060</strong>)</li>
                                        <li>Rückmeldung bewertet (<strong>P065</strong>)</li>
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
                    <a href="#top">Zum Seitenanfang
                        <span class="glyphicon glyphicon-arrow-up"></span>
                    </a>
                </p>
            </div>
    @endforeach

    <div class="row">
        <h4 id="reminders">Erinnerungen</h4>

        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Ereignis</th>
                    <th>Erklärung</th>
                    <th>Aktion</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Erhalt des ersten Schreibimpulses</td>
                    <td>
                        Gilt für alle Patienten die heute (bzw. zum Testdatum) Ihren ersten Schreibtag haben.
                    </td>
                    <td>
                        <form method="POST" action="/admin/send-reminders/first">
                            {{ csrf_field() }}
                            <input class="btn-link" value="Benachrichtigungen versenden" type="submit" />
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Erhalt eines Folge-Schreibimpulses</td>
                    <td>
                        Gilt für alle Patienten die heute (bzw. zum Testdatum) Ihren neuen Schreibtag haben.
                    </td>
                    <td>
                        <form method="POST" action="/admin/send-reminders/new">
                            {{ csrf_field() }}
                            <input class="btn-link" value="Benachrichtigungen versenden" type="submit" />
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Aktueller Schreibimpuls unbearbeitet</td>
                    <td>
                        Gilt für alle Patienten die Ihren aktuellen Schreibimpuls 5 Tage lang nicht bearbeitet haben.
                    </td>
                    <td>
                        <form method="POST" action="/admin/send-reminders/due">
                            {{ csrf_field() }}
                            <input class="btn-link" value="Benachrichtigungen versenden" type="submit" />
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="text-right" style="clear: both">
            <a href="#top">Zum Seitenanfang
                <span class="glyphicon glyphicon-arrow-up"></span>
            </a>
        </p>
    </div>

    </div>
@endsection
