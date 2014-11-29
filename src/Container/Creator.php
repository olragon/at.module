<?php

namespace Drupal\at\Container;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class Creator
{

    /** @var string */
    private $fileName;

    /** @var string */
    private $namespace;

    /** @var string */
    private $className;

    /** @var ContainerBuilder */
    private $builder;

    public function __construct($fileName, $namespace, $className)
    {
        $this->fileName = $fileName;
        $this->namespace = $namespace;
        $this->className = $className;
        $this->builder = new ContainerBuilder();
    }

    /**
     * @return \Drupal\at\Container
     */
    public function create()
    {
        foreach ($this->getExtensions() as $extension) {
            $this->builder->registerExtension($extension);
            $this->builder->loadFromExtension($extension->getAlias());
        }

        foreach ($this->getCompilerPasses() as $compilerPass) {
            $compilerPass = is_string($compilerPass[0]) ? new $compilerPass[0] : $compilerPass[0];
            $compilerPassType = isset($compilerPass[1]) ? $compilerPass[1] : PassConfig::TYPE_BEFORE_OPTIMIZATION;
            $this->builder->addCompilerPass($compilerPass, $compilerPassType);
        }

        return $this->containerDump($this->builder);
    }

    protected function getExtensions()
    {
        $extensions = [];
        foreach (module_invoke_all('at_container_extension_info') as $name => $info) {
            $extensions[$name] = at_newv($info['class'], isset($info['arguments']) ? $info['arguments'] : []);
        }
        return $extensions;
    }

    protected function getCompilerPasses()
    {
        $compilerPasses = [];
        return $compilerPasses;
    }

    protected function containerDump()
    {
        $this->builder->compile();

        // Dump
        $dumper = new PhpDumper($this->builder);
        file_put_contents($this->fileName, $dumper->dump(['namespace' => $this->namespace, 'class' => $this->className]));

        // Return container
        require_once $this->fileName;
        return new $this->className;
    }

}
