<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Twig\Extension;

use Sonata\BlockBundle\Templating\Helper\BlockHelper;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Exception\PageNotFoundException;
use Sonata\PageBundle\Model\PageBlockInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\SnapshotPageProxy;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\InitRuntimeInterface;
use Twig\TwigFunction;
use Sonata\PageBundle\Twig\Extension\PageExtension as BasePageExtension;

/**
 * PageExtension.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageExtension extends AbstractExtension implements InitRuntimeInterface
{

    /**
     * @var CmsManagerSelectorInterface
     */
    private $cmsManagerSelector;

    /**
     * @var SiteSelectorInterface
     */
    private $siteSelector;

    /**
     * @var array
     */
    private $resources;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var BlockHelper
     */
    private $blockHelper;

    /**
     * @var HttpKernelExtension
     */
    private $httpKernelExtension;

    /**
     * @var bool
     */
    private $hideDisabledBlocks;

    /**
     * @param CmsManagerSelectorInterface $cmsManagerSelector A CMS manager selector
     * @param SiteSelectorInterface $siteSelector A site selector
     * @param RouterInterface $router The Router
     * @param BlockHelper $blockHelper The Block Helper
     * @param HttpKernelExtension $httpKernelExtension
     * @param bool $hideDisabledBlocks
     */
    public function __construct(CmsManagerSelectorInterface $cmsManagerSelector, SiteSelectorInterface $siteSelector, RouterInterface $router, BlockHelper $blockHelper, HttpKernelExtension $httpKernelExtension, $hideDisabledBlocks = false)
    {
        $this->cmsManagerSelector = $cmsManagerSelector;
        $this->siteSelector = $siteSelector;
        $this->router = $router;
        $this->blockHelper = $blockHelper;
        $this->httpKernelExtension = $httpKernelExtension;
        $this->hideDisabledBlocks = $hideDisabledBlocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions = [
            new TwigFunction('sonata_site_head_code', [$this, 'getHeadCode'], ['is_safe' => ['html']]),
            new TwigFunction('sonata_site_footer_code', [$this, 'getFooterCode'], ['is_safe' => ['html']]),
            new TwigFunction('sonata_site_body_code', [$this, 'getBodyCode'], ['is_safe' => ['html']])
        ];

        return $functions;
    }

    public function getHeadCode()
    {
        $site = $this->siteSelector->retrieve();

        if ($site) {

            return $site->getHeadCode();
        } else {

            return null;
        }
    }

    public function getFooterCode()
    {
        $site = $this->siteSelector->retrieve();

        if ($site) {

            return $site->getFooterCode();
        } else {

            return null;
        }
    }

    public function getBodyCode()
    {
        $site = $this->siteSelector->retrieve();

        if ($site) {

            return $site->getBodyCode();
        } else {

            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_page_custom';
    }
}
