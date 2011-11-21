         
        <?php slot('sharethis') ?><ul id="sharethis" style="float:right;"></ul><?php end_slot()?>

        
        <?php include_partial('viewer/schedule') ?>
              
        
<!--        <script language="javascript">
          window.onload = function ()
                {
                loadSchedule('<?php //echo url_for1('authentication') ?>','<?php //echo url_for1('link_to_share') ?>');

            };
        </script>  -->
        
        
        <?php if($shared){ ?>
            <script language="javascript">
                   
                   Event.observe(window, 'load', function() {
                        loadShareThis(<?php echo "'".$code."'"; ?>);
                   });
            </script>
        <?php } ?>
        