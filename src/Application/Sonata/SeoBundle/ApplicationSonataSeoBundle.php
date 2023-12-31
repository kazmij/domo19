<?php

namespace Application\Sonata\SeoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Application\Sonata\SeoBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 *
 * References:
 * @link http://symfony.com/doc/current/book/bundles.html
 */
class ApplicationSonataSeoBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataSeoBundle';
    }
}