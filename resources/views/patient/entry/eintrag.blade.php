<?php
  $visible = $isPatient || $isTherapist &&  $isTherapist && $EntryInfo['status'] >= 'E040';
  $editable = $isPatient && $EntryInfo['status'] < 'E040';
?>
@if($visible)
  <h3><i class="fa fa-book" aria-hidden="true"></i> Tagebucheintrag</h3>

  @if ($EntryInfo['week'] == 1)
  <label>Klicken Sie auf die Situationen, um diese auszuklappen</label>
    <div class="panel-group m-t-1" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Situation 1
            </a>
          </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
          <div class="panel-body">
            <div class="form-group">
              <label for="situation0_description">Beschreiben Sie die Situation
                <a href="javascript:void(0)" tabindex="0" data-toggle="popover" data-placement="right" data-html="true" data-trigger="focus"
                   title="Bitte beschreiben Sie für jede Situation" data-content="
               <ul>
                <li>wann und</li>
                <li>mit wem sie sich ereignete,</li>
                <li>was Sie sich in der Situation gewünscht haben,</li>
                <li>was die andere Person sagte oder tat, </li>
                <li>was Sie selbst sagten oder taten und</li>
                <li>wie die Begegnung endete. </li>
               </ul>
               ">
                  <i class="fa fa-question-circle"></i>
                </a>
              </label>

              @if($editable)
                <textarea class="form-control js-auto-size" id="situation0_description" name="situation0_description">{{$EntryInfo['answer'][0]['description']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][0]['description'])) !!}
                </p>
              @endif
            </div>
            <p><strong>Nachdem Sie jetzt eine bedeutsame Situation geschildert haben, möchte ich Sie bitten Ihren Text noch
              einmal durchzulesen und darauf zu achten, ob Sie die drei Komponenten eines Beziehungserlebnisses
              deutlich machen konnten.<br/>Bitte beantworten Sie dafür die folgenden drei Fragen!</strong>
            </p>
            <div class="form-group">
              <label for="situation0_expectations">Was waren Ihre Wünsche und Erwartungen in dieser Situation?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation0_expectations" name="situation0_expectations">{{$EntryInfo['answer'][0]['expectation']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][0]['expectation'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation0_their_reaction">Wie haben die anderen an der Situation beteiligten reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation0_their_reaction" name="situation0_their_reaction">{{$EntryInfo['answer'][0]['their_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][0]['their_reaction'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation0_my_reaction">Wie haben Sie auf die anderen reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation0_my_reaction" name="situation0_my_reaction">{{$EntryInfo['answer'][0]['my_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][0]['my_reaction'])) !!}
                </p>
              @endif
            </div>

          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingTwo">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Situation 2
            </a>
          </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
          <div class="panel-body">

            <div class="form-group">
              <label for="situation1_description">Beschreiben Sie die Situation</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation1_description" name="situation1_description">{{$EntryInfo['answer'][1]['description']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][1]['description'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation1_expectations">Was waren Ihre Wünsche und Erwartungen in dieser Situation?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation1_expectations" name="situation1_expectations">{{$EntryInfo['answer'][1]['expectation']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][1]['expectation'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation1_their_reaction">Wie haben die anderen an der Situation beteiligten reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation1_their_reaction" name="situation1_their_reaction">{{$EntryInfo['answer'][1]['their_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][1]['their_reaction'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation1_my_reaction">Wie haben Sie auf die anderen reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation1_my_reaction" name="situation1_my_reaction">{{$EntryInfo['answer'][1]['my_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][1]['my_reaction'])) !!}
                </p>
              @endif
            </div>

          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingThree">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Situation 3
            </a>
          </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
          <div class="panel-body">

            <div class="form-group">
              <label for="situation2_description">Beschreiben Sie die Situation</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation2_description" name="situation2_description">{{$EntryInfo['answer'][2]['description']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][2]['description'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation2_expectations">Was waren Ihre Wünsche und Erwartungen in dieser Situation?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation2_expectations" name="situation2_expectations">{{$EntryInfo['answer'][2]['expectation']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][2]['expectation'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation2_their_reaction">Wie haben die anderen an der Situation beteiligten reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation2_their_reaction" name="situation2_their_reaction">{{$EntryInfo['answer'][2]['their_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][2]['their_reaction'])) !!}
                </p>
              @endif
            </div>
            <div class="form-group">
              <label for="situation2_my_reaction">Wie haben Sie auf die anderen reagiert?</label>
              @if($editable)
                <textarea class="form-control js-auto-size" id="situation2_my_reaction" name="situation2_my_reaction">{{$EntryInfo['answer'][2]['my_reaction']}}</textarea>
              @else
                <p>
                  {!! nl2br(e($EntryInfo['answer'][2]['my_reaction'])) !!}
                </p>
              @endif
            </div>

          </div>
        </div>
      </div>
    </div>
  @else
    @if ($editable)
      <textarea class="form-control js-auto-size" id="reflection"  name="reflection">{{$EntryInfo['reflection']}}</textarea>
    @else
      <p>{!! nl2br(e($EntryInfo['reflection'])) !!}</p>
    @endif
  @endif
  @if($submittable && $isPatient && $EntryInfo['status'] < "E050")
    <button class="btn pull m-t-1" name="entryButton" value="saveDirty" onClick="submitDirty();">Zwischenspeichern</button>
  @endif
@endif
