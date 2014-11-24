<?php

/**
 * Invokes the "new" operator with a vector of arguments. There is no way to
 * call_user_func_array() on a class constructor, so you can instead use this
 * function:
 *
 * $obj = at_newv($class_name, $argv);
 *
 * That is, these two statements are equivalent:
 *
 * $pancake = new Pancake('Blueberry', 'Maple Syrup', true);
 * $pancake = newv('Pancake', array('Blueberry', 'Maple Syrup', true));
 *
 * @param  string  The name of a class.
 * @param  list    Array of arguments to pass to its constructor.
 * @return obj     A new object of the specified class, constructed by passing
 *                  the argument vector to its constructor.
 */
function at_newv($class_name, $argv = array())
{
    $reflector = new ReflectionClass($class_name);
    if ($argv) {
        return $reflector->newInstanceArgs($argv);
    }
    return $reflector->newInstance();
}

/**
 * Get modules that depends on a specific module.
 *
 * @param string $baseModule
 * @param string $configFile
 * @return array
 * @see system_list()
 */
function at_modules($baseModule = 'at_base', $configFile = '', $modules = [])
{
    $cache = &drupal_static(__FUNCTION__, []);

    if (isset($cache[$baseModule][$configFile])) {
        return $cache[$baseModule][$configFile];
    }

    if (!empty($modules)) {
        return $cache[$baseModule][$configFile] = $modules;
    }

    return $cache[$baseModule][$configFile] = at()
        ->getModuleFetcher($configFile, $baseModule)
        ->fetch(system_list('module_enabled'));
}

/**
 * Care about site caching.
 *
 * @param  array|string $options
 * @param  Closure|string $callback
 * @param  array  $arguments
 * @return mixed
 */
function at_cache($options, $callback = NULL, $arguments = array())
{
    // User prefer string as cache options
    // Style: $id OR $id,$ttl OR $id,~,$bin OR $id,~,~ OR $id,$ttl,$bin
    if (is_string($options)) {
        @list($id, $ttl, $bin) = explode(',', $options);

        $options = array(
            'id'  => $id,
            'ttl' => is_null($ttl) ? NULL : ('~' === $ttl ? NULL : $ttl),
            'bin' => is_null($bin) ? NULL : ('~' === $bin ? NULL : $bin),
        );
    }

    if (isset($options['cache_id'])) {
        $options['id'] = $options['cache_id'];
        unset($options['cache_id']);
    }

    foreach (array('callback', 'options') as $k) {
        if (!empty($kk) && isset($options[$k])) {
            $kk = $options[$k];
        }
    }

    return at()->getCache($options, $callback, $arguments)->get();
}
