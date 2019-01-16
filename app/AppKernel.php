<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Application\Sonata\PageBundle\ApplicationSonataPageBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // These are the other bundles the SonataAdminBundle relies on
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            // And finally
            new Sonata\AdminBundle\SonataAdminBundle(),

            new Sonata\CacheBundle\SonataCacheBundle(),

            new Sonata\SeoBundle\SonataSeoBundle(),

            new Sonata\NotificationBundle\SonataNotificationBundle(),

            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),

            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            new Sonata\PageBundle\SonataPageBundle(),
            new \Application\Sonata\PageBundle\ApplicationSonataPageBundle(),


            //new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new FOS\CKEditorBundle\FOSCKEditorBundle(),
            //new Sonata\NewsBundle\SonataNewsBundle(),
            new Sonata\UserBundle\SonataUserBundle(),
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\ClassificationBundle\SonataClassificationBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Sonata\DatagridBundle\SonataDatagridBundle(),

            new \Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new \Application\Sonata\ClassificationBundle\ApplicationSonataClassificationBundle(),
            new \Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
            new \Application\Sonata\IntlBundle\ApplicationSonataIntlBundle(),
            new \Application\Sonata\SeoBundle\ApplicationSonataSeoBundle(),
            //new \Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),
            //new \Application\Sonata\SliderBundle\ApplicationSonataSliderBundle(),
            //new \Application\Sonata\ProductBundle\ApplicationSonataProductBundle(),

            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Pix\SortableBehaviorBundle\PixSortableBehaviorBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),

            new Liip\ImagineBundle\LiipImagineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            new \Application\Sonata\MainBundle\ApplicationSonataMainBundle(),
            new \Application\Sonata\OfferBundle\ApplicationSonataOfferBundle(),

            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),

            new JMS\TranslationBundle\JMSTranslationBundle(),

            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            new Snc\RedisBundle\SncRedisBundle(),

            new Symfony\Bundle\MakerBundle\MakerBundle()
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setParameter('container.autowiring.strict_mode', true);
            $container->setParameter('container.dumper.inline_class_loader', true);

            $container->addObjectResource($this);
        });
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
