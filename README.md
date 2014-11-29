AT [![Build Status](https://travis-ci.org/v3kwip/at.module.svg?branch=7.x-1.x)](https://travis-ci.org/v3kwip/at.module)
=======

> Your attention please:  Do not use this module until it's under beta phase.

This module provides API for other Drupal modules, there's no features for end
users.

To use this module for your module, edit MODULE.info file, add this line:

```ini
dependencies[] = at
```

### 1. Service container

1.1 Define services

The module provide a bridge to integrate Symfony Dependency Injection component.
We can define ./services.yml file in module's root directory.

```yaml
# file: MODULE.services.yml
services:
    expression_language:
        class: 'Symfony\Component\ExpressionLanguage\ExpressionLanguage'
```

> Tip: To provide your own extension, check `hook_at_container_extension_info()`

1.2 Get services

```php
$service = at()->getContainer()->get($id);
```
