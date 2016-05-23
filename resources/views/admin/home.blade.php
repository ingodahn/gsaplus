@extends('layouts.master')
@section('title', 'Softwaretest-Kontrollzentrum')

@section('additional-head')
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
                    <a href="#patients">Patienten</a>
                </li>
                <li>
                    <a href="#therapists">Therapeuten</a>, und
                </li>
                <li>
                    <a href="#admins">Administratoren</a>
                </li>
            </ul>
            <p>einsehen und neue Benutzer anlegen.</p>
            <p>Im Notfall können Sie <a href="#reminders">anstehende Benachrichtigungen versenden</a> -
                z.B. wenn der Mail-Server ausgefallen ist und die Benachrichtigungen nicht versendet werden konnten.</p>
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
                            <th>Code</th>
                            <th>Status</th>
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

    </div>
@endsection
