<?php

namespace Application\Sonata\OfferBundle\Block;

use Application\Sonata\MainBundle\Model\BuyModel;
use Application\Sonata\MainBundle\Model\Email;
use Application\Sonata\OfferBundle\Entity\Offer;
use Sonata\FormatterBundle\Block\FormatterBlockService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;

/**
 * Class OfferCategories
 */
class OfferCategories extends AbstractAdminBlockService
{
    use ContainerAwareTrait;


    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);

        $resolver->setDefault('template', '@ApplicationSonataOffer/Block/offerCategories.html.twig');
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $categoryManager = $this->container->get('sonata.classification.manager.category');
        $categories = $categoryManager->getCategories(Offer::CATEGORIES_CONTEXT);

        return $this->renderResponse($blockContext->getTemplate(), array(
            'categories' => $categories
        ), $response);
    }

}