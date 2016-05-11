@extends('layouts.master')
@section('title', 'Kommentiertes Tagebuch')

@section('content')
    <div class="container">
<h2>Ihr Tagebuch</h2>
        @if($isTherapist)
            <p>
                Dies ist das Tagebuch von <em>{{ $PatientName }}</em>.
            </p>
        @endif
    @for ($i=1;$i<$Week; $i++)
    <div class="commentedWeek">
        <h3>Woche {{ $i }}</h3>
        <div class="impuls">
            {{nl2br(e($Assignments[$i]['problem']))}}
            </div>
        <div class="answer">
            {{nl2br(e($Assignments[$i]['answer']))}}
            </div>
        <div class="comment">
            {{nl2br(e($Assignments[$i]['comment']))}}
        </div>
        </div>
    @endfor

@endsection