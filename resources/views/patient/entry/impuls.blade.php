<h3>Schreibimpuls</h3>
@if($isPatient || $isTherapist && $EntryInfo['status'] >= 'E020')
  <p>{!! nl2br(e($EntryInfo['problem'])) !!}</p>
@elseif($isTherapist && $EntryInfo['status'] < 'E020')
  <p>

    <div class="form-group">
      <script type="text/javascript">
        function getProblemTemplate(inhalt)
        {
          alert(document.getElementById("problem").innerHTMLvalue);
          if (inhalt=="Kein Muster gewählt")
          {
            document.getElementById("problem").innerHTML="";
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
              document.getElementById("problem").innerHTML=xmlhttp.responseText;
            }
          }
          xmlhttp.open("GET","/AssignmentTemplate?templateTitle="+inhalt,true);
          xmlhttp.send();
        }
      </script>
      <p>
    <label for="choose_template">Muster:</label>
    <input type="text" name="template_title" id="template_title" size="50">
      <select name="choose_template" id="choose_template" onchange=getProblemTemplate(this.value) size="1">
        <option selected>Kein Muster gewählt</option>
        @foreach($Problems as $title)
          <option>{{{$title}}}</option>
        @endforeach
      </select>
  </p>
  <p>
      <label for="problem">Problem bearbeiten</label>

  </p>
      <textarea class="form-control js-auto-size" id="problem" name="problem">{{$EntryInfo['problem']}}</textarea>
    </div>
  </p>
@endif
