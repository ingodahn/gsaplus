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
                        label: 'Gesundheit (-1: Keine Angabe)',
                        fill: false,
                        data: [
                                @for ($w=1; $w <= $Week; $w++)
                            {{ $Health[$w] }},
                                @endfor
                            {{ $Health[$Week] }}
                        ]
                    }]
                },
                options: {
                }});
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
                        label: 'Arbeitsfähigkeit (-1: Keine Angabe)',
                        fill: false,
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
                <h4><i class="fa fa-flag" aria-hidden="true"></i> Schreibimpuls</h4>
                {!! nl2br(e($Assignments[1]['problem'])) !!}
            </div>
            @if ($Assignments[1]['dirty'])
                <div class="answerNotSubmitted">
                    @else
                        <div class="answer">
                            @endif
                            <h4><i class="fa fa-book" aria-hidden="true"></i> Wocheneintrag</h4>
                            @if ($isTherapist && $Assignments[1]['dirty'])
                                Nicht eingereicht.
                            @elseif($Assignments[1]['answer']=="")
                                Nicht bearbeitet
                            @else
                                @for ($j=1;$j<=2;$j++)
                                    @if (isset($Assignments[1]['answer'][$j-1]['description']))
                                        <div class="situation">
                                            <h4><i class="fa fa-sitemap" aria-hidden="true"></i> Situation {!! $j !!}</h4>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['description'] !!}
                                            </div>
                                            <h5>Meine Erwartungen</h5>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['expectation'] !!}
                                            </div>
                                            <h5>Meine Reaktion</h5>
                                            <div class="sitPart">
                                                {!! $Assignments[1]['answer'][$j-1]['myReaction'] !!}
                                            </div>
                                            <h5>Die Reaktionen der anderen</h5>
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
                <hr/>
                <h3>Woche {{ $i }}</h3>
                <div class="impuls">
                    <h4><i class="fa fa-flag" aria-hidden="true"></i> Schreibimpuls</h4>
                    <p>{!! nl2br(e($Assignments[$i]['problem'])) !!}</p>
                </div>
                @if ($Assignments[$i]['dirty'])
                    <div class="answerNotSubmitted">
                        @if ($isTherapist)
                           <p>Nicht eingereicht.</p>
                        @else
                            <h4><i class="fa fa-book" aria-hidden="true"></i> Wocheneintrag (nicht eingereicht)</h4>
                            <p>{!! nl2br(e($Assignments[$i]['answer'])) !!}</p>
                            </div>
                        @endif
                            @else
                            <div class="answer">
                                <h4><i class="fa fa-book" aria-hidden="true"></i> Wocheneintrag</h4>
                                <p>{!! nl2br(e($Assignments[$i]['answer'])) !!}</p>
                                </div>
                                <div class="comment">
                                    <h4><i class="fa fa-commenting" aria-hidden="true"></i> Rückmeldung Ihres Online-Therapeuten</h4>
                                    <p>{!! nl2br(e($Assignments[$i]['comment'])) !!}</p>
                                </div>
                                @endif
                    </div>
    @endfor
</div>

@endsection
