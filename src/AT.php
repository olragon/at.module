<?php

namespace Drupal\at;

use Drupal\at\Container\Creator;
use Drupal\at\Drupal\DrupalCacheAPI;
use Drupal\at\ModuleFetcher;

class AT
{

    protected $containerNamespace = '';
    protected $containerClass = 'AT_Container';

    /** @var DrupalCacheAPI */
    private $drupalCacheAPI;

    /**
     * @param string $baseModule
     * @param string $configFile
     * @return ModuleFetcher
     */
    public function getModuleFetcher($baseModule, $configFile)
    {
        return new ModuleFetcher($baseModule, $configFile);
    }

    /**
     * @return AT_Container
     */
    public function getContainer()
    {
        if (!$container = &drupal_static(__FUNCTION__)) {
            $fileName = variable_get('file_private_path', '') . '/at.container.php';
            if (file_exists($fileName)) {
                require_once $fileName;
                return $container = new $this->containerClass;
            }
            $creator = new Creator($fileName, $this->containerNamespace, $this->containerClass);
            $container = $creator->create();
        }
        return $container;
    }

    public function getDrupalCacheAPI()
    {
        if (NULL === $this->drupalCacheAPI) {
            $this->drupalCacheAPI = new DrupalCacheAPI();
        }
        return $this->drupalCacheAPI;
    }

    public function getCache($options, $callback, $arguments)
    {
        return new Cache($options, $callback, $arguments, $this->getDrupalCacheAPI());
    }

    public function setDrupalCacheAPI($api)
    {
        $this->drupalCacheAPI = $api;
    }

}
