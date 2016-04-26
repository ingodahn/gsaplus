@extends('layouts.master')
@section('title', 'Test Page')

@section('additional-head')
    <style>
        ul.nav {
            display: none;
        }

        pre.sf-dump {
            font: 18px Monaco,Consolas,monospace !important;
            background-color: AliceBlue !important;
            z-index: 99998 !important;
        }

        .sf-dump-note {
            color: Indigo !important;
        }

        .sf-dump-key {
            color: DodgerBlue !important;
        }

        .sf-dump-str {
            color: #ef7676 !important;
        }

        #button-container {
            margin-top: 0.5em;
        }
    </style>

    <script>
        $( document ).ready(function() {
            $('.sf-dump-note').contents().filter(function() {
                return this.nodeType == 3
            }).each(function(){
                this.textContent = this.textContent.replace('array','Einträge');
            });
        });
    </script>
@endsection

@section('content')
    <br/>
    <div class="container">
        <p>Für den Benutzer <strong>{{ $info['name'] }}</strong> sind die folgenden Daten gespeichert<small>*</small>:</p>

        {{ dump($info) }}

        <small>* alle errechneten Werte (wie z.B. die Patientwoche) beziehen sich auf das aktuelle Testdatum</small>

        <p id="button-container">
            <form action="/test/dump-info/{{ $info['name'] }}/save" method="POST" class="pull-right">
                {{ csrf_field() }}
                <button class="btn btn-primary" type="submit">Datenabbild auf dem Server speichern</button>
                <a onclick="window.close()" class="btn btn-default">Schließen</a>
            </form>
        </p>

    </div>
@endsection




