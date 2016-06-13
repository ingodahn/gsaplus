@extends('layouts.master')
@section('title', 'Softwaretest-Kontrollzentrum')

@section('additional-head')
    <style>
        td {
            vertical-align:middle !important;
        }

        th {
            white-space : nowrap;
            vertical-align:middle !important;
        }

        ul.nav {
            display: none;
        }

        hr {
            border-top: 1px dashed #8c8b8b;
        }

        .btn-link, th {
            margin-bottom: 4px;
            white-space: normal;
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
            <h3>Softwaretest-Kontrollzentrum</h3>

            <p>Auf dieser Seite können Sie das <a href="#config">Testdatum ändern</a> und die im System vorhandenen</p>
            <ul>
                <li>
                    <a href="#patients">Patienten</a>
                </li>
                <li>
                    <a href="#therapists">Therapeuten</a>, und
                </li>
                <li>
                    <a href="#admins">Administratoren</a>
                </li>
            </ul>
            <p>einsehen.</p>
            <p>Es ist möglich sich im Namen eines bestimmten Benutzers einzuloggen, die Mails eines
                Benutzers einzusehen und das Datum auf ein in der Zukunft liegendes Schreibdatum zu ändern
                (falls der Benutzer ein Patient ist).<br/></p>
            <p>Patienten werden (in der Regel am Folgetag) automatisch benachrichtigt falls</p>
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
            <p>Die zukünftige Historie der Nutzer wird automatisch gelöscht, wenn Sie ein älteres Datum setzen
                und dieses nicht in der Vergangenheit liegt. Dabei werden zukünftige Kommentare, Rückmeldungen,
                etc. entfernt. Die aktuelle Aufgabe bleibt von der Aktion unberührt <strong><em>(!)</em></strong>.</p>
        </div>

        <div class="row">
            <hr />
        </div>

        <div class="row">
            <h4 id="config">Testdatum</h4>

            <form role="form" action="/test/settings" method="post">
                {{ csrf_field() }}
                <table class="table table-striped table-bordered table-hover" style="margin-bottom: 1em;">
                    <thead class="hide">
                        <tr>
                            <th>Einstellung</th>
                            <th>Wert</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-xs-7">Testdatum (leer = aktuelles Datum)</td>
                            <td>
                                <div class='input-group date' id='datetimepicker'>
                                    <input name="test_date" type='text' value="{{ $settings['testDate'] }}" class="form-control">
                                                <span class="input-group-addon">
                                                  <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                    </input>
                                </div>
                                <script type="text/javascript">
                                    $(function () {
                                        $('#datetimepicker').datetimepicker({
                                            locale: 'de',
                                            format: 'DD.MM.YYYY'
                                        });
                                    });
                                </script>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="pull-right">
                    <button type="submit" class="btn btn-default" name="reset_settings">Aktuelles Datum wiederherstellen</button>
                    <button type="submit" class="btn btn-primary" name="save_settings">Speichern</button>
                </p>
            </form>
            <p class="text-right" style="clear: both; padding-top: 0.8em;">
                <a href="#top">Zum Seitenanfang
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </a>
            </p>
        </div>

        <div class="row">
            <hr />
        </div>

        <div class="row">
            <h4 id="set_date">Zum nächsten Datum</h4>
            <p>Hier können Sie zu einem Folgedatum springen.</p>
            <form method="POST" action="/test/next-date"
                  class="pull-right floating-btn-form">
                {{ csrf_field() }}
                <input name="relative_date_string" value="tomorrow" hidden/>
                <input class="btn btn-primary" value="Zum nächsten Tag" type="submit" />
            </form>
            <p class="text-right" style="clear: both">
                <br/>
                <a href="#top">Zum Seitenanfang
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </a>
            </p>
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
                            <th>Login</th>
                            @if($role == UserRole::PATIENT)
                                <th>Status</th>
                                <th class="text-center">Zum Erinnerungs&shy;datum</th>
                                <th class="text-center">Zum Fristende</th>
                                <th class="text-center">Zum nächsten Schreibdatum</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="/test/dump-info/{{$user['name'] }}" target="_blank">
                                    {{ $user['name'] }}
                                </a>
                            </td>
                            <td>
                                <a href="https://www.mailinator.com/inbox2.jsp?public_to={{$user['name'] }}#/#public_maildirdiv" target="_blank">
                                    {{ $user['email'] }}
                                </a>
                            </td>
                            <td>
                                <form method="POST" action="/test/login/{{ $user['name'] }}" target="_blank">
                                    {{ csrf_field() }}
                                    <input class="btn-link" value="Login" type="submit" />
                                </form>
                            </td>
                            @if($role == UserRole::PATIENT)
                                <td style="white-space: nowrap !important">
                                    <em>{{ $user['patientStatus']  }}</em>
                                    <a href="javascript:void(0)" tabindex="0" data-toggle="popover" data-placement="right"
                                       data-html="true" data-trigger="focus" title="Es gibt folgende Patienten-Status (P)"
                                       data-content="<ul>
                                        <li>Registriert (<strong>P020</strong>)</li>
                                        <li>Entlassungsdatum erfasst (<strong>P025</strong>)</li>
                                        <li>Entlassen (<strong>P028</strong>)</li>
                                        <li>Schreibimpuls erhalten (<strong>P030</strong>)</li>
                                        <li>Tagebucheintrag in Bearbeitung und zwischengespeichert (<strong>P040</strong>)</li>
                                        <li>Tagebucheintrag gemahnt (<strong>P045</strong>)</li>
                                        <li>Tagebucheintrag abgeschickt (<strong>P050</strong>)</li>
                                        <li>Ihr Onlinetherapeut hat geantwortet - bitte bewerten Sie seine Rückmeldung. (<strong>P060</strong>)</li>
                                        <li>Tagebucheintrag und Rückmeldung abgeschlossen (<strong>P065</strong>)</li>
                                        <li>Mitarbeit beendet (<strong>P130</strong>)</li>
                                        <li>Interventionszeit beendet (<strong>P140</strong>)</li>
                                        </ul>">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                </td>
                                @if(isset($user['dateOfReminder']) && $user['patientStatus'] < 'P130')
                                    <td class="text-center">
                                        <form method="POST" action="/test/next-reminder/{{ $user['name'] }}">
                                            {{ csrf_field() }}
                                            <input class="btn-link" value="Zum {{$user['dateOfReminder']}}" type="submit" />
                                        </form>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <small><em>kein Schreibimpuls</em></small>
                                    </td>
                                @endif
                                @if(isset($user['dateOfDeadline']) && $user['patientStatus'] < 'P130')
                                    <td class="text-center">
                                        <form method="POST" action="/test/next-deadline/{{ $user['name'] }}">
                                            {{ csrf_field() }}
                                            <input class="btn-link" value="Zum {{$user['dateOfDeadline']}}" type="submit" />
                                        </form>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <small><em>kein Schreibimpuls</em></small>
                                    </td>
                                @endif
                                @if($user['nextWritingDate'] && $user['patientStatus'] < 'P130')
                                    <td class="text-center">
                                        <form method="POST" action="/test/next-date/{{ $user['name'] }}">
                                            {{ csrf_field() }}
                                            <input class="btn-link" value="Zum {{$user['nextWritingDate']}}" type="submit" />
                                        </form>
                                    </td>
                                @else
                                    <td class="text-center">
                                        <small><em>kein Folgedatum</em></small>
                                    </td>
                                @endif
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

    </div>
@endsection
