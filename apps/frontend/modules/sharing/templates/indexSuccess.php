<?php slot('sharing-hvr') ?>border-top-menu<?php end_slot()?>

<?php if(true):?>
<?php slot('leftbar') ?>
    
    <ul id="leftbar">
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200703080','0'); return false;" >
            <li class="list-ppl">
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200703080.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Jhonny Pincay</div>
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200801033','1'); return false;" >
            <li class="list-ppl">   
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200801033.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Efrain Astudillo</div>       
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200800845','2'); return false;" >
            <li class="list-ppl">   
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200800845.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Jefferson Rubio</div>       
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200904001','3'); return false;" >
            <li class="list-ppl">   
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200904001.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Leonardo Hernandez</div>       
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200902278','4'); return false;" >
            <li class="list-ppl">   
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200902278.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Jefferson Cunalata</div>       
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
        <a href="#" onclick="cleanHover(); loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200626851','5'); return false;" >
            <li class="list-ppl">   
                <img title="" alt="" src="http://www.academico.espol.edu.ec/imgEstudiante/200626851.jpg" class="userphoto pplpic"/>
                <div class="ppl-name">Felix Rivas</div>       
                <img class="loader" style="display: none;" title="" alt="" src="/images/loader-small.gif" />
            </li>
        </a>
    </ul>

    <script language="javascript">
        function cleanHover() {
            var lists = document.getElementsByClassName("list-ppl");
            for(var i=0;i<lists.length;i++)
            {
                document.getElementsByClassName('loader')[i].style.display= 'none';
                document.getElementsByClassName("list-ppl")[i].setAttribute("style",'background-color: none;');   
            }
            
        }
    </script>
    <script language="javascript">
          window.onload = function ()
            {
                loadSchedulePpl('<?php echo url_for1('scheduler') ?>','200703080','0');

            };
    </script>  
    
<?php end_slot()?>
<?php endif;?>
    
<?php include_partial('viewer/schedule') ?>
    
<script language="javascript">
    document.getElementById('pageBody').style.marginLeft = '0';
    document.getElementById("pageBody").setAttribute("style",document.getElementById("pageBody").getAttribute("style")+' float:right;');
</script>
