<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link type="image/x-icon" href="/images/espol.ico" rel="shortcut icon"/>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <script type="text/javascript">var switchTo5x=false;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">
            stLight.options({
                    publisher:'b3fa1493-6864-4224-a9d8-6efd89f94db8',
                    tracking:'google',
                    embeds:'true',
                    onhover: false,
                    displayText: 'TAWS - ESPOL'
            });
    </script>   
  </head>
  <body>
      <div id="menu">
          <div id="menu-holder">
              <ul>
              
              <li class="inline hvr <?php if (!include_slot('sharing-hvr')): ?><?php endif ?>">
                  <a class="inline-padding" href="<?php echo url_for1('sharing') ?>">Horarios Compartidos <strong>(5)</strong></a>
              </li>
              <span class="inline inline-padding separator">|</span>
              
              <li class="inline hvr">
                  <img class="inline userphoto" src="http://www.academico.espol.edu.ec/imgEstudiante/<?php echo $sf_user->getUsuario()->getMatricula() ?>.jpg" alt="" title=""></img>
                  <a class="inline" href="#">
                    <div class="inline name-padding"><?php echo $sf_user->getNombre() ?></div>
                  
                    <div class="inline inline-padding pulldown"></div>
                  </a>
<!--                  <ul class="navigation" role="navigation">
                      <li><a class="navSubmenu" href="#" >Configuración de tus datos</a></li>
                      <li><a class="navSubmenu" href="#" >Configuración de la privacidad</a></li>
                      <li class="menuDivider"></li>
                      <li><a class="navSubmenu" href="#" >Salir</a></li>
                  </ul>-->
              </li>
              </ul>
          </div>
      </div>
      <div id="header">
            <div class="wrapper">
                <a href="<?php echo url_for('homepage') ?>">
                    <img id="logo" src="/img/banner3.png" alt="Scheduler" />
                </a>
                
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
                                        <label id="label_search" onclick="loadSchedule('<?php echo url_for1('authentication') ?>','<?php echo url_for1('link_to_share') ?>'); return false;">Ver Horario</label>
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
                    <?php if (!include_slot('sharethis')): ?><?php endif ?>
                    <ul id="message-error" style="text-align:center; width: auto; color: red; font-size: 15px; padding-left: 75px;"></ul>
                </ul>
                <?php if (!include_slot('leftbar')): ?><?php endif ?>
      
                <?php echo $sf_content ?>
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
                <a href="javascript:scroll(0,0)"><div id="to-top" class="poshytip" title="To top"></div></a>
            </div>
        </div>
  </body>
</html>
