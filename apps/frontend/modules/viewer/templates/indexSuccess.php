        <script src="/js/base/wz_tooltip.js" type="text/javascript"></script>
        <script src="/js/base/tip_balloon.js" type="text/javascript"></script>
        
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
        
        
        <?php if($shared){ ?>
            <script language="javascript">
                   
                   Event.observe(window, 'load', function() {
                        loadShareThis(<?php echo "'".$code."'"; ?>);
                   });
            </script>
        <?php } ?>
        