        <?php use_stylesheet('autocomplete') ?>
        <?php use_javascript('protoculous-effects-shrinkvars.js') ?>
        <?php use_javascript('textboxlist.js') ?>
        <?php use_javascript('autocomplete.js') ?>
        <?php use_javascript('share.js') ?> 

        <?php slot('class-schedule') ?>
            loadSchedule('<?php echo url_for1('ownschedule') ?>','<?php echo url_for1('link_to_share') ?>');
        <?php end_slot()?>
        
        <?php slot('class-selection-hvr') ?>border-top-menu<?php end_slot()?>
        
        <?php slot('sharethis') ?><ul id="sharethis" style="float:right;"></ul><?php end_slot()?>
        
        <?php slot('loader-bar') ?>
            <ul id="loader-bar" style="display: none; text-align:center; width: auto; font-weight: bold; font-size: 13px; padding-left: 75px; padding-bottom: 5px;">
                Cargando... <img src="/images/loader-bar.gif" alt="" />
            </ul>
        <?php end_slot()?>
        <?php slot('sharing-to') ?>
           <div id="sharing" >
                
            <form action="test_submit" method="get" accept-charset="utf-8" id="search-bar" style="display: none;">
                <div id="close-search" onclick="closeShare();"></div>
                <li id="facebook-list" class="input-text">
                <img id="loader-search" src="/images/ajax-loader-circular.gif" alt="" style="display: none;"/>
            
                  <input type="text" value="" id="facebook-demo" />
                  <div id="facebook-auto">

                    <div class="default">Escriba el primer nombre y dos apellidos...</div> 
                    <ul class="feed">
                        
                    </ul>
                  </div>

                </li>
              
            </form>  
            <div id="sharing-buttons" class="sharing">
                <a id="share" href="#" onclick="share('<?php echo url_for1('sharing_save') ?>'); return false;" class="button add" style="display: none;">Compartir</a>
                <a id="start-share" onclick="shareStart();" href="#" class="button add" >Comparte tu horario</a>
            </div>
            </div>
        <?php end_slot()?>
        
        <?php include_partial('viewer/schedule') ?>
        
        <script language="JavaScript">
            document.observe('dom:loaded', function() {

              // init
              tlist2 = new FacebookList('facebook-demo', 'facebook-auto',{fetchFile:'<?php echo url_for1('usuario_json') ?>'});

            });    
        </script>
        
        <script language="javascript">
          window.onload = function ()
                {
                loadSchedule('<?php echo url_for1('ownschedule') ?>','<?php echo url_for1('link_to_share') ?>');

            };
        </script>  
        
        
        <?php if($shared): ?>
            <script language="javascript">
                   
                   Event.observe(window, 'load', function() {
                        loadShareThis(<?php echo "'".$code."'"; ?>);
                   });
            </script>
        <?php endif; ?>