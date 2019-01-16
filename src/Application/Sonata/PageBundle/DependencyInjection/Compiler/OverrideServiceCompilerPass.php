<?php

namespace Application\Sonata\PageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

/**
 * Class OverrideServiceCompilerPass
 * @package Shopmacher\IsaBodyWearBundle\DependencyInjection\Compiler
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{

    /**
     * Overwrite project specific services
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('sonata.page.kernel.exception_listener');
        $definition->setClass(\Application\Sonata\PageBundle\Listener\ExceptionListener::class);
        $tags = $definition->getTags();
        if(isset($tags['kernel.event_listener'][0]['priority'])) {
            $tags['kernel.event_listener'][0]['priority'] = 1;
            $definition->setTags($tags);
        }
    }
}