<?php

require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, isset($env) ? $env : 'soaptest', isset($debug) ? $debug : true);
require_once($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');

sfContext::createInstance($configuration);

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));