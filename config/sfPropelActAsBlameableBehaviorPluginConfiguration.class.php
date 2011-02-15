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
    $this->configuration->getEventDispatcher()->connect('plugin.pre_install', array($this, 'postInstall'));
  }
  
  /**
   * Listen for event: plugin.post_install
   * 
   * @param sfEvent $event
   */
  public function postInstall(sfEvent $event) 
  {
      $command = new propelConfigureblameablebehaviorTask();
      $command->run();
  }
    
}
