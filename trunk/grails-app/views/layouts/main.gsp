<html>
  <head>
    <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

    <META http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <META http-equiv="PRAGMA" content="NO-CACHE">
    <META name="AUTHOR" content="TAWS">
    <META name="AUTHOR" content="Denisse Cayetano">
    <META name="AUTHOR" content="Marco Calderon">
    <META name="AUTHOR" content="Allan Avendano">
    <META name="COPYRIGHT" content="&copy; 2008 TAWS">
    <META name="KEYWORDS" content="ESPOL, FIEC, horarios espol, horarios, TAWS, Scheduler Viewer, Planificador de Horarios">
    <META name="DESCRIPTION" content="Mediante el Schedule Viewer los
          estudiantes de ESPOL pueden ver sus horarios y descargarlo
          en formato PDF al ingresar su n&uacute;mero de matr&iacute;cula.">
    <g:javascript src="base/prototype.js" />
    <g:javascript src="widget.js" />
    <g:javascript src="functions.js" />
    
    <title><g:layoutTitle default="Horarios Espol" /></title>
    <link rel="stylesheet" href="${resource(dir:'css',file:'main.css')}" />
    <link rel="stylesheet" href="${resource(dir:'css',file:'my.css')}" />
    <link rel="stylesheet" href="${resource(dir:'css',file:'scheduler.css')}" />
    <link rel="stylesheet" href="${resource(dir:'css',file:'widget.css')}" />
    <link rel="shortcut icon" href="${resource(dir:'images',file:'espol.ico')}" type="image/x-icon" />
  <g:layoutHead />
  <g:javascript library="application" />
</head>
<body>
  <div id="wrapper">
    <div id="header"></div>
    <div id="leftcolumn">
      <div id="instrucciones">
        <label id="masdetalle">M&aacute;s detalle:</label> <br>
        Como <label class="note">AULA</label>,
        <label class="note">PARALELO</label> y
        <label class="note">PROFESOR</label>, pase el mouse sobre cualquier de sus materia.
      </div>
      <div id="finder">
        <form method="POST" onsubmit="loadSchedule(); return false;">
          Usuario:           <br>
          <INPUT id="user" type="text" name="user" value="" size="9" style="margin-left:40px;"><br>
          Contrase&ntilde;a: <br>
          <INPUT id="password" type="password" name="password" value="" size="9" style="margin-left:40px;"><br><br>
          <input id="verhorario" type="submit" value="Ver Horario!" name="verhorario" />
          <!--
          Matr&iacute;cula: <INPUT id="matricula" type="text" name="matricula" value="" size="9" maxlength="9">
          <br>
          <p>
            <label>
              <input id="tipoHorario" name="TipoHorario" type="radio" value="clases" checked="" />Clases</label>
            <br>
            <label> <input type="radio" name="TipoHorario" value="examen" /> Examen</label>
            <br>
            <input id="verhorario" type="submit" value="Ver Horario!" name="verhorario" />
          </p>
          -->
        </form>
      </div>
    </div>
    <div id="rightcolumn">
      <g:layoutBody />
    </div>
    <div id="spinner" class="spinner" style="display:none;">
      <img src="${resource(dir:'images',file:'spinner.gif')}" alt="${message(code:'spinner.alt',default:'Loading...')}" />
    </div>
    <div id="taws" style="opacity: 0.4;"></div>
  </div>
  <script language="javascript">
      var pageTracker = _gat._getTracker("UA-5343888-2");
      pageTracker._trackPageview();
  </script>
  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-5343888-2']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
</body>
</html>