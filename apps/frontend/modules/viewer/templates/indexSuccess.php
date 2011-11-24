        <?php slot('class-schedule') ?>
            loadSchedule('<?php echo url_for1('ownschedule') ?>','<?php echo url_for1('link_to_share') ?>');
        <?php end_slot()?>
        
        <?php slot('class-selection-hvr') ?>border-top-menu<?php end_slot()?>
        
        <?php slot('sharethis') ?><ul id="sharethis" style="float:right;"></ul><?php end_slot()?>
        
        <?php slot('loader-bar') ?>
            <ul id="loader-bar" style="display: none; text-align:center; width: auto; font-weight: bold; font-size: 13px; padding-left: 75px;">
                Cargando... <img src="/images/loader-bar.gif" alt="" />
            </ul>
        <?php end_slot()?>
        <?php slot('sharing-to') ?>
            <div class="sharing">
                <a href="#" class="button add">Comparte tu horario</a>
            </div>
        <?php end_slot()?>
        <?php include_partial('viewer/schedule') ?>
              
        
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