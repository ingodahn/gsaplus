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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.2/Chart.min.js"></script>
        <h3>Meine Gesundheit</h3>
        <canvas id="health" ></canvas>
               <script>
            var ctx = document.getElementById("health");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                    datasets: [{
                        label: 'Gesundheit',
                        data: [
                                @for ($w=1; $w <= $Week; $w++)
                            {{ $Health[$w] }},
                                @endfor
                            {{ $Health[$Week] }}
                        ]
                    }]
                },
                options: {

                }
            });
        </script>
        <h3>Meine Arbeitsfähigkeit</h3>
        <canvas id="wai" ></canvas>
        <script>
            var ctx = document.getElementById("wai");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                    datasets: [{
                        label: 'Arbeitsfähigkeit',
                        data: [
                            @for ($w=1; $w <= $Week; $w++)
                            {{ $Wai[$w] }},
                            @endfor
                            {{ $Wai[$Week] }}
                        ]
                    }]
                },
                options: {

                }
            });
        </script>
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
                            @elseif($Assignments[1]['answer']=="")
                                Nicht bearbeitet
                            @else
                                @for ($j=1;$j<=2;$j++)
                                    @if (isset($Assignments[1]['answer'][$j-1]['description']))
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

        @for ($i=2;$i<=$Week; $i++)
            <div class="commentedWeek">
                <h3>Woche {{ $i }}</h3>
                <div class="impuls">
                    {!! nl2br(e($Assignments[$i]['problem'])) !!}
                </div>
                @if ($Assignments[$i]['dirty'])
                    <div class="answerNotSubmitted">
                        @else
                            <div class="answer">
                                @endif
                                @if ($isTherapist)
                                    Nicht eingereicht.
                                @else
                                    {!! nl2br(e($Assignments[$i]['answer'])) !!}
                                @endif

                            </div>
                            <div class="comment">
                                {!! nl2br(e($Assignments[$i]['comment'])) !!}
                            </div>
                    </div>
    @endfor

@endsection