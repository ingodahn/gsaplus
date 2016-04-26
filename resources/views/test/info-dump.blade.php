@extends('layouts.master')
@section('title', 'Test Page')

@section('additional-head')
    <style>
        ul.nav {
            display: none;
        }

        pre.sf-dump {
            font: 18px Monaco,Consolas,monospace !important;
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
    <br/>
    <div class="container">
        {{ dump($info) }}

        <p class="pull-right" id="button-container">
            <a href="/test" class="btn btn-primary">Zurück</a>
        </p>

    </div>
@endsection




