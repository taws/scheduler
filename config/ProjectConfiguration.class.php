<?php

require_once '/Developer/symfony-1.4.8//lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfXssSafePlugin');
//    $this->enablePlugins('sfNuSoapPlugin');    
    
  }
}
