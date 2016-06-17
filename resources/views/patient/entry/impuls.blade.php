<h3><i class="fa fa-flag" aria-hidden="true"></i> Schreibimpuls</h3>

@if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
  <p>{!! nl2br(e($EntryInfo['problem'])) !!}</p>
@elseif($isTherapist && $EntryInfo['status'] < 'E020')
  <p>
    <script type="text/javascript">
      function getProblemTemplate(inhalt)
      {
        if (inhalt=="Keine Vorlage gewählt")
        {
          document.getElementById("template_title").value="";
          document.getElementById("problem").value="";
          return;
        }
        if (window.XMLHttpRequest)
        {
          // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
          xmlhttp=new XMLHttpRequest();
        }
        else
        {
          // AJAX mit IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
          {
            document.getElementById("template_title").value=inhalt;
            document.getElementById("problem").value=xmlhttp.responseText;
          }
        }
        xmlhttp.open("GET","/AssignmentTemplate?templateTitle="+inhalt,true);
        xmlhttp.send();
      }
    </script>



<div class="panel panel-default">

<div class="panel-heading">Schreibimpuls-Vorlagen auswählen und bearbeiten (optional) <a href="javascript:void(0)" tabindex="0" data-toggle="popover" data-placement="right" data-html="true" data-trigger="focus" title="Auswahlhilfe und Editor für die Schreibimpuls-Vorlagen" data-content="
  Hier können Sie entweder einen Schreibimpuls aus einer Vorlage verwenden, oder eigene Schreibimpulse anlegen und bearbeiten.<br><br>
  <strong>Schreibimpuls-Vorlage auswählen</strong><br>
  Wählen Sie aus der Liste der Vorlagen die gewünschte Position aus. <strong>Achtung:</strong> Ihr möglicherweise zuvor eingegebener Schreibimpuls wird durch den Schreibimpuls aus der Vorlage überschrieben.<br><br>
  <strong>Schreibimpuls-Vorlage anlegen</strong><br>
  Füllen Sie das Feld 'Schreibimpuls bearbeiten' unten vollständig aus. Vergeben Sie einen Namen für Ihre neue Vorlage und klicken Sie anschließend auf 'Schreibimpuls-Vorlage erstellen/bearbeiten'.<br><br>
  <strong>Schreibimpuls-Vorlage überarbeiten</strong><br>
  Sie können den Inhalt Ihrer Schreibimpuls-Vorlage überarbeiten, indem Sie die passende Vorlage auswählen, den Text des Schreibimpulses unten anpassen und anschließend auf 'Schreibimpuls-Vorlage erstellen/bearbeiten' klicken. Die Vorlage wird mit dem neuen Inhalt abgespeichert. Den Titel der Vorlage können Sie nicht anpassen. Wenn Sie den Titel verändern, wird eine neue Vorlage angelegt.
  "><i class="fa fa-question-circle"></i></a></div>
<div class="panel-body">

  <p>
    <span class="form-group">
      <label for="choose_template">Vorlage auswählen <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Achtung: Auswahl überschreibt den Schreibimpuls unten!</label>
      <select name="choose_template" id="choose_template" onchange=getProblemTemplate(this.value) class="form-control">
        <option selected>Keine Vorlage ausgewählt</option>
          @foreach($Problems as $title)
            <option>{{{$title}}}</option>
          @endforeach
      </select>
    </span>
  </p>

  <p>
    <span class="form-group">
      <label for="template_title">Name der Vorlage</label>
      <input type="text" name="template_title" id="template_title" class="form-control">
    </span>
  </p>

  <p>
    <button class="btn" onClick="newAssignment()">Schreibimpuls-Vorlage erstellen/bearbeiten</button>
  </p>

</div>
</div>

  <p>
    <div class="form-group">
      <h4><label for="problem">Schreibimpuls bearbeiten</label></h4>
      <textarea class="form-control js-auto-size" id="problem" name="problem">{{$EntryInfo['problem']}}</textarea>
    </div>
  </p>
@endif
