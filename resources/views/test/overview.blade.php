@extends('layouts.master')
@section('title', 'Softwaretest-Kontrollzentrum')

@section('additional-head')
    <style>
        td {
            vertical-align:middle !important;
        }
        
        ul.nav {
            display: none;
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
            }, 1000);
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
            <p>Auf dieser Seite können sie die im System vorhandenen</p>
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
                (falls der Benutzer ein Patient ist).<br/>
            <p>Patienten können <a href="#reminders">daran erinnert</a> werden,
                dass Sie den ersten bzw. einen Folge-Schreibimpuls erhalten oder den aktuellen Schreibimpuls 5 Tage lang
                nicht bearbeitet haben.</p>
            <p>In den <a href="#config">Einstellungen</a> kann das aktuelle Test-Datum gewählt werden. Zudem
                können bestimmte Erinnerungen automatisch verschickt werden, wenn sich das Test-Datum ändert.
                Standardmäßig sind alle Benachrichtigungen aktiviert.</p>
            <p>Das Testdatum kann ebenso <a href="#set_date">schrittweise durchlaufen werden</a>.</p>
            <p>Am Ende der Seite können <a href="#clear_dates">nicht mehr benötigte Schreibdaten gelöscht werden</a>
                (diese werden berechnet wenn an neue Schreibimpulse erinnert wird).</p>
            <hr/>
        </div>

        @foreach($infos as $role => $users)
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

            @if($role == UserRole::PATIENT)
                <em>Wichtig: </em>die Links in der Tabelle beziehen sich alle auf das aktuelle Datum. D.h. mit diesen
                können Sie nur zum nächsten Schreibimpuls springen. Bitte <a href="#set_date">durchlaufen Sie die
                Zeit schrittweise</a> oder <a href="#config">setzen Sie das Test-Datum</a> manuell um die Wochen
                >= 2 zu testen.</p>
            @endif

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-Mail</th>
                        <th>Login</th>
                        @if($role == UserRole::PATIENT)
                        <th>Status</th>
                        <th>Zeitsprung</th>
                        <th>Weiter Springen</th>
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
                            @if($user['nextWritingDate'] && $user['patientStatus'] < 'P130')
                            <td>
                                <form method="POST" action="/test/next-date/{{ $user['name'] }}">
                                    {{ csrf_field() }}
                                    <input class="btn-link" value="Zum nächsten Schreibdatum" type="submit" />
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="/test/next-date/{{ $user['name'] }}/{{ config('gsa.reminder_period_in_days') }}">
                                    {{ csrf_field() }}
                                    <input class="btn-link" value="nochmal (+) 5 Tage" type="submit" />
                                </form>
                            </td>
                            @else
                                <td class="text-center">
                                    <small><em>... kein Folgedatum ...</em></small>
                                </td>
                                <td class="text-center">
                                    <small><em>... kein Folgedatum ...</em></small>
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
                            <form method="POST" action="/test/send-reminders/first">
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
                            <form method="POST" action="/test/send-reminders/new">
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
                            <form method="POST" action="/test/send-reminders/due">
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

        <div class="row">
            <h4 id="config">Einstellungen</h4>

            <form role="form" action="/test/settings" method="post">
                {{ csrf_field() }}
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Einstellung</th>
                        <th>Wert</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="col-xs-7">Aktuelles Datum</td>
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
                        <tr>
                            <td>
                                Automatisch an ersten Schreibimpuls erinnern
                            </td>
                            <td>
                                <input type="checkbox" class="pull-right" name="first_reminder" value="1" {{ $settings['firstReminder'] ? 'checked' : ''}}>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Automatisch an neuen Schreibimpuls erinnern
                            </td>
                            <td>
                                <input type="checkbox" class="pull-right" name="new_reminder" value="1" {{ $settings['newReminder'] ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Automatisch an Schreibimpuls erinnern, der in Kürze fällig ist
                            </td>
                            <td>
                                <input type="checkbox" class="pull-right" name="due_reminder"  value="1" {{ $settings['dueReminder'] ? 'checked' : ''}}>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bei einer Erinnerung automatisch den nächsten Schreibtag berechnen
                            </td>
                            <td>
                                <input type="checkbox" class="pull-right" name="calc_next_writing_date"  value="1" {{ $settings['calcNextWritingDate'] ? 'checked' : ''}}>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="pull-right">
                    <button type="submit" class="btn btn-default" name="reset_settings">Einstellungen wiederherstellen</button>
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

        <div class="row">
            <h4 id="clear_dates">Schreibdaten bereinigen</h4>

            <form role="form" action="/test/remove-distant-dates" method="post">
                {{ csrf_field() }}
                <p>Sie können jedes berechnete (und nicht mehr benötigte) Schreibdatum entfernen falls sich
                    das System ungewöhnlich verhalten sollte.</p>

                <button type="submit" class="btn btn-primary pull-right" name="remove_unnecessary_dates">Schreibdaten bereinigen</button>
            </form>
            <p class="text-right" style="clear: both">
                <br/>
                <a href="#top">Zum Seitenanfang
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </a>
            </p>
        </div>

    </div>
@endsection
