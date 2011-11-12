<html>
  <head>
    <title>Scheduler</title>
    <meta name="layout" content="main" />
  </head>
  <body>
    <g:javascript src="base/wz_tooltip.js" />
    <g:javascript src="base/tip_balloon.js" />
    <div id="pageBody">
      <div style="text-align: right; width: 100%; margin-bottom:11px; margin-left:45px;">
      <a name="fb_share" type="button_count" share_url="http://www.taws.espol.edu.ec/scheduler/" href="http://www.facebook.com/sharer.php">
        Compartir
      </a>
      <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
      </div>
      <div id="container-div">
          <script language="javascript">
            var mygrid = new Grid({
                  initHour: 7,
                  initMinute: 0,
                  endHour: 22,
                  endMinute: 0,
                  editable: 0,
                  width: '743px'
            });
          </script>
       </div>
    </div>
  </body>
</html>
