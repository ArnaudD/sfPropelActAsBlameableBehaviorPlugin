This symfony plugin allows to track the author of a creation, modification or deletion of a Propel model object.

It is only compatible with Propel-1.5.x

Install
=======

Via symfony
-----------

    symfony plugin:install sfPropelActAsBlameableBehaviorPlugin


Manually
--------

    git clone git://github.com/ArnaudD/sfPropelActAsBlameableBehaviorPlugin.git

Declare the behavior in your 'config/propel.ini' :

    ; sfPropelActAsBlameableBehaviorPlugin
    propel.behavior.blameable.class = plugins.sfPropelActAsBlameableBehaviorPlugin.lib.behavior.SfPropelBehaviorBlameable


Usage
=====

Add the blameable behavior to your table in config/schema.yml

    propel:
      my_table:
        id: ~
        created_by: { type: integer }
        ...
        _propel_behaviors:
          blameable:

Define the getId method in your myUser class :

    function getId ()
    {
      // TODO
    }

Re-build your model classes :

    symfony propel:build-model

Enjoy
