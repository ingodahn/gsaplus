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
        <div class="commentedWeek">
            <h3>Woche 1</h3>
            <div class="impuls">
                {!! nl2br(e($Assignments[1]['problem'])) !!}
            </div>
            @if ($Assignments[1]['dirty'])
                <div class="answerNotSubmitted">
                    @else
                        <div class="answer">
                            @endif
                            @if ($isTherapist && $Assignments[1]['dirty'])
                                Nicht eingereicht.
                            @else
                                @for ($j=1;$j<=2;$j++)
                                    @if ($Assignments[1]['answer'][$j-1]['description'])
                                        <div class="situation">
                                            <h4>Situation {!! $j !!}</h4>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['description'] !!}
                                            </div>
                                            <h4>Meine Erwartungen</h4>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['expectation'] !!}
                                            </div>
                                            <h4>Meine Reaktion</h4>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['myReaction'] !!}
                                            </div>
                                            <h4>Die Reaktionen der anderen</h4>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['theirReaction'] !!}
                                            </div>
                                            </div>
                                        @endif
                                @endfor
                            @endif
                        </div>
        </div>

        @for ($i=2;$i<$Week; $i++)
            <div class="commentedWeek">
                <h3>Woche {{ $i }}</h3>
                <div class="impuls">
                    {{nl2br(e($Assignments[$i]['problem']))}}
                </div>
                @if ($Assignments[$i]['dirty'])
                    <div class="answerNotSubmitted">
                        @else
                            <div class="answer">
                                @endif
                                @if ($isTherapist)
                                    Nicht eingereicht.
                                @else
                                    {{nl2br(e($Assignments[$i]['answer']))}}
                                @endif

                            </div>
                            <div class="comment">
                                {{nl2br(e($Assignments[$i]['comment']))}}
                            </div>
                    </div>
    @endfor

@endsection