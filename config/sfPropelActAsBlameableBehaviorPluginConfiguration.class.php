<?php

/**
 * sfPropelActAsBlameableBehaviorPlugin configuration.
 * 
 * @package     sfPropelActAsBlameableBehaviorPlugin
 * @subpackage  config
 * @author      Arnaud Didry <arnaud@didry.info>
 */
class sfPropelActAsBlameableBehaviorPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '0.1.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->configuration->getEventDispatcher()->connect(
      'plugin.post_install',
      array($this, 'postInstall'));
  }
  
  /**
   * Listen for event: plugin.post_install
   * 
   * @param sfEvent $event
   */
  public function postInstall(sfEvent $event) 
  {
    $iniFile = sfConfig::get('sf_config_dir').'/propel.ini';
    $iniContent = file_get_contents($iniFile);

    if($iniContent === false)
      new sfException ("config/propel.ini not found");

    if(strpos($iniContent, 'propel.behavior.blameable.class') === false)
    {
      $blameableBehaviorConfig = "\n"
        ."; sfPropelActAsBlameableBehaviorPlugin\n"
        ."propel.behavior.blameable.class = plugins.sfPropelActAsBlameableBehaviorPlugin.lib.behavior.SfPropelBehaviorBlameable\n";
      file_put_contents ($iniFile, $blameableBehaviorConfig, FILE_APPEND);
    }
  }
    
}
