<?php

if (!isset($_SERVER['SYMFONY']))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

//require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
//sfCoreAutoload::register();

require_once dirname(__FILE__).'/../fixtures/project/config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);
$configuration->setRootDir ( dirname(__FILE__).'/../fixtures/project/');
sfContext::createInstance($configuration);

//$configuration = new sfProjectConfiguration(dirname(__FILE__).'/../fixtures/project');
//$configuration->enablePlugins ('sfPropel15Plugin');
require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function sfPropelActAsBlameableBehaviorPlugin_autoload_again($class)
{
  $autoload = sfSimpleAutoload::getInstance();
  $autoload->reload();
  return $autoload->autoload($class);
}
spl_autoload_register('sfPropelActAsBlameableBehaviorPlugin_autoload_again');

require_once dirname(__FILE__).'/../../config/sfPropelActAsBlameableBehaviorPluginConfiguration.class.php';
$plugin_configuration = new sfPropelActAsBlameableBehaviorPluginConfiguration($configuration, dirname(__FILE__).'/../..', 'sfPropelActAsBlameableBehaviorPlugin');

// Load sfPropel15Plugin
require_once dirname(__FILE__).'/../../../sfPropel15Plugin/config/sfPropel15PluginConfiguration.class.php';
$plugin_configuration = new sfPropel15PluginConfiguration($configuration, dirname(__FILE__).'/../..', 'sfPropel15Plugin');

// Build model + sql
$configuration->initializePropel ('frontend');

/*
// Load sql
$databaseManager = new sfDatabaseManager($configuration);
$propel->setConfiguration($configuration);
$propel->run();

$connection = $databaseManager->getDatabase('blameable_test');
 */
