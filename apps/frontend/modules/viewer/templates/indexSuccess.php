        <script src="/js/base/wz_tooltip.js" type="text/javascript"></script>
        <script src="/js/base/tip_balloon.js" type="text/javascript"></script>
        <div id="header">
            <div class="wrapper">
                <img id="logo" src="/img/banner3.png" alt="Scheduler" />
                <div class="top-search">
                    <form  method="get" id="searchform" action="#">
                        <div>
                            <table cellspacing="8">
                                <tr>
                                    <td class="access">Usuario</td>
                                    <td class="access">Contrase&ntilde;a</td>
                                </tr>
                                <tr>
                                    <td><input type="text" value="" name="user" id="user" /></td>
                                    <td><input type="password" value="" name="password" id="password" /></td>
                                    <td>
                                        <label id="label_search" onclick="loadSchedule(); return false;">Ver Horario</label>
                                    </td>
                                    <td id="finder">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
        <div id="main">
            <div class="wrapper">
                
                <div id="content">
                    <ul class="tabs">
                        <ul id="sharethis" style="float:right;"></ul>
                        <ul id="message-error" style="text-align:center; width: auto; color: red; font-size: 15px; padding-left: 75px;"></ul>
                    </ul>
                    <div id="pageBody" style="margin-top: 17px; margin-left: 92px;">
                        <div id="container-div">
                          <script language="javascript">
                            var mygrid = new Grid({
                                  initHour: 7,
                                  initMinute: 0,
                                  endHour: 22,
                                  endMinute: 0,
                                  editable: false,
                                  width: '743px',
                                  height: '575px'
                            });
                          </script>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="bottom">
            <div class="wrapper">
                <div id="bottom-text">2011 <a href="http://taws.espol.edu.ec/"> TAWS</a> </div>
                <ul class="social ">
                    <li><a href="https://www.facebook.com/tawsespol" class="poshytip  facebook" title="En Facebook"></a></li>
                    <li><a href="http://twitter.com/#!/taws_espol" class="poshytip twitter" title="Follow us"></a></li>
                    <li><a href="http://taws.espol.edu.ec/" class="poshytip dribbble" title="Home"></a></li>
                </ul>
                <div id="to-top" class="poshytip" title="To top"></div>
            </div>
        </div>
        
        <?php if($shared){ ?>
            <script language="javascript">
                   
                   Event.observe(window, 'load', function() {
                        loadShareThis(<?php echo "'".$code."'"; ?>);
                   });
            </script>
        <?php } ?>
        