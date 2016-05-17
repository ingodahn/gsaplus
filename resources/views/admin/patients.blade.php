@extends('layouts.master')
@section('title', 'Nutzerinformation')

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
        <p>Aktuell sind die folgenden Patienten registriert:</p>

        {{ dump($info) }}

        <a onclick="window.close()" class="btn btn-default pull-right">Schließen</a>
    </div>
@endsection