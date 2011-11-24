<?php slot('sharing-selection-hvr') ?>border-top-menu<?php end_slot()?>

<?php slot('href-class-schedule') ?><?php echo url_for('homepage') ?><?php end_slot()?>


<?php if(sizeof($comparten)!=0):?>
<?php slot('leftbar') ?>
    
    <ul id="leftbar">
        
        <?php $i=0; foreach($comparten as $user_comparten): ?>
            <?php if($i==0): ?>
                <script language="javascript">
                      window.onload = function ()
                        {
                            loadSchedulePpl('<?php echo url_for1('scheduler') ?>','<?php echo $user_comparten->getToken(); ?>','0');

                        };
                </script>  
            <?php endif; ?>
                    
            <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','<?php echo $user_comparten->getToken(); ?>','<?php echo $i; ?>'); return false;" >
                <li class="list-ppl">
                    <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/<?php echo $user_comparten->getMatricula(); ?>.jpg" class="userphoto pplpic"/>
                    <div class="ppl-name"><?php echo Utility::FNameFLast ($user_comparten->getNombres(), $user_comparten->getApellidos()); ?></div>
                    <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
                </li>
            </a>
            <?php $i=$i+1 ?>
        <?php endforeach; ?>
        
    </ul>

    <script language="javascript">
        function cleanHover() {
            request.abort();
            
            var lists = document.getElementsByClassName("list-ppl");
            for(var i=0;i<lists.length;i++)
            {
                document.getElementsByClassName('loader')[i].style.display= 'none';
                document.getElementsByClassName("list-ppl")[i].setAttribute("style",'background-color: none;');   
            }
            
        }
    </script>
    
    
<?php end_slot()?>
<?php else: ?>
    <ul id="leftbar">
        <li class="list-ppl" style="text-align: center;">
            Ninguna persona ha compartido su horario contigo
        </li>
    </ul>
<?php endif;?>
    
<?php include_partial('viewer/schedule') ?>
    
<script language="javascript">
    document.getElementById('pageBody').style.marginLeft = '0';
    document.getElementById("pageBody").setAttribute("style",document.getElementById("pageBody").getAttribute("style")+' float:right;');
</script>
