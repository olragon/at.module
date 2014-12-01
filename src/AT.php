<?php

namespace Drupal\at;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Drupal\at\Container\Creator;
use Drupal\at\Drupal\DrupalCacheAPI;
use Drupal\at\Hooks\Implementations;
use Drupal\at\ModuleFetcher;

class AT
{

    protected $containerNamespace = '';
    protected $containerClass = 'AT_Container';

    /** @var Implementations */
    private $hookImplementations;

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

            // Make sure composer's autoloader is registered
            require_once drupal_get_path('module', 'composer_manager') . '/composer_manager.module';
            composer_manager_register_autoloader();

            $creator = new Creator($fileName, $this->containerNamespace, $this->containerClass);
            $container = $creator->create();
        }
        return $container;
    }

    /**
     * Get service by ID.
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->getContainer()->get($id);
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

    public function getHookImplementations()
    {
        if (NULL === $this->hookImplementations) {
            $this->hookImplementations = new Implementations();
        }
        return $this->hookImplementations;
    }

    public function setHookImplementations($hookImplementations)
    {
        $this->hookImplementations = $hookImplementations;
        return $this;
    }

    /**
     * @return \Drupal\at\JsonSchemaValidator
     */
    public function getJsonSchemaValidator()
    {
        return $this->getContainer()->get('json_schema.validator');
    }

    /**
     * Get key-value storage entity manager.
     * @param string $name
     * @return \Doctrine\KeyValueStore\EntityManager
     */
    public function getKeyValueStorageEntityManager($name = 'default')
    {
        static $ran = FALSE;
        if (!$ran && ($ran = TRUE)) {
            $base_dir = composer_manager_vendor_dir() . '/doctrine/key-value-store/';
            AnnotationRegistry::registerFile($base_dir . '/lib/Doctrine/KeyValueStore/Mapping/Annotations/Entity.php');
            AnnotationRegistry::registerFile($base_dir . '/lib/Doctrine/KeyValueStore/Mapping/Annotations/Id.php');
            AnnotationRegistry::registerFile($base_dir . '/lib/Doctrine/KeyValueStore/Mapping/Annotations/Transient.php');
        }
        return at()->getContainer()->get('kvs.em.' . $name);
    }

}
