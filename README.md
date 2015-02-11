@module [![Build Status](https://travis-ci.org/v3kwip/at.module.svg?branch=7.x-1.x)](https://travis-ci.org/v3kwip/at.module)
=======

> Your attention please:  Do not use this module until it's under beta phase.

This module provides API for other Drupal modules, there's no features for end
users.

## Install

You must be familiar to Drush and composer.

```bash
drush dl composer-7.x-1.x-dev composer_manager xautoload
drush vset composer_manager_autobuild_packages 0
drush en -y composer_manager xautoload at
drush composer-rebuild
cd sites/default/files/composer/
composer update --no-dev
```

## Using

To use this module for your module, edit MODULE.info file, add this line:

```ini
dependencies[] = at
```

Documentation for each functionality of provided under ./docs.

## Features

1. Bridge for **Symfony Dependency Injection** component.
2. Bridge for **Symfony YAML** component
3. Bridge for **Doctrine [Key-value storage](https://github.com/doctrine/KeyValueStore)**
4. Bridge for **JSON Schema validator**.
5. Bridge for **Symfony Event Dispatcher** component.
6. Including **VarDumper [VarDumper Component](http://symfony.com/doc/current/components/var_dumper/index.html)**
7. Provide Watchdog **PSR-3 Logger**
