<h3>Schreibimpuls</h3>
@if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
  <p>{!! nl2br(e($EntryInfo['problem'])) !!}</p>
@elseif($isTherapist && $EntryInfo['status'] < 'E020')
  <p>
    <script type="text/javascript">
      function getProblemTemplate(inhalt)
      {
        if (inhalt=="Kein Muster gewählt")
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

      <p>
  <span class="form-group">
    <label for="template_title">Muster:</label>
    <input type="text" name="template_title" id="template_title" size="50">
    </span>
  <span class="form-group">
    <label for="vhoose_template" class="sr-only">Musterauswahl:</label>
      <select name="choose_template" id="choose_template" onchange=getProblemTemplate(this.value) size="1">
        <option selected>Kein Muster gewählt</option>
        @foreach($Problems as $title)
          <option>{{{$title}}}</option>
        @endforeach
      </select>
  </span>
    <button type="submit" class="btn btn-primary" name="entryButton" value="newAssignment">Neuer Musterimpuls</button>
  </p>
  <p>
    <div class="form-group">
      <label for="problem">Schreibimpuls bearbeiten</label>
      <textarea class="form-control js-auto-size" id="problem" name="problem">{{$EntryInfo['problem']}}</textarea>
    </div>
  </p>
@endif
