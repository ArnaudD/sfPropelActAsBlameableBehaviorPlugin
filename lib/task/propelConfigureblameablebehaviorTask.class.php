<?php

class propelConfigureblameablebehaviorTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = 'propel';
    $this->name             = 'configure-blameable-behavior';
    $this->briefDescription = 'Configure Propel Blameable Behavior Plugin';
    $this->detailedDescription = <<<EOF
The [propel:configure-blameable-behavior|INFO] task does things.
Call it with:

  [php symfony propel:configure-blameable-behavior|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
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
