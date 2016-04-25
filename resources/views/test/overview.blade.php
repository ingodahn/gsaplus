@extends('layouts.master')
@section('title', 'Test Page')

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
            (falls der Benutzer ein Patient ist).</p>
        <p>Patienten können <a href="#reminders">daran erinnert</a> werden,
            dass Sie den ersten bzw. einen Folge-Schreibimpuls erhalten oder den aktuellen Schreibimpuls 5 Tage lang
            nicht bearbeitet haben.</p>
        <p>In den <a href="#config">Einstellungen</a> kann das aktuelle Test-Datum gewählt werden. Zudem
            können bestimmte Erinnerungen automatisch verschickt werden, wenn sich das Test-Datum ändert.</p>

        <hr/>

        @foreach($infos as $role => $users)
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
                        <th>Zeitsprung</th>
                        <th>Weiter Springen</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
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
                            @if($user['nextWritingDate'])
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
        @endforeach

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

        <p>Wenn ein Patient an einen aktuellen Schreibimpuls erinnert wird, so wird ebenso das <em>nächste
            Schreibdatum (+1 Woche)</em> berechnet.</p>

        <br/>

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
                                <input name="test_date" type='text' value="{{ $settings['testDate'] }}" class="form-control" required>
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
                </tbody>
            </table>
            <p class="pull-right">
                <button type="submit" class="btn btn-primary" name="reset_settings">Wiederherstellen</button>
                <button type="submit" class="btn btn-primary" name="save_settings">Speichern</button>
            </p>
        </form>

    </div>
@endsection
