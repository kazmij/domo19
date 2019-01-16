<?php

namespace Application\Sonata\MainBundle\Block;

use Application\Sonata\MainBundle\Model\BuyModel;
use Application\Sonata\MainBundle\Model\Email;
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
 * Class Buy
 */
class Buy extends AbstractAdminBlockService
{
    use ContainerAwareTrait;


    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);

        $resolver->setDefault('template', '@ApplicationSonataMain/Block/buy.html.twig');
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $categoryManager = $this->container->get('sonata.classification.manager.category');

        $formOptions = [
            'csrf_protection' => false,
            'method' => 'GET'
        ];
        $buyModel = new BuyModel();
        $form = $this->container->get('form.factory')->createNamed(null, 'sonata_main_block_buy_form', $buyModel, $formOptions);

        $form->handleRequest($request);

        $offersQb = $this->container->get('application_sonata_offer.admin.offer_helper')->getOffersQb($buyModel);
        /* @var $results \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination */
        $results = $this->container->get('knp_paginator')->paginate($offersQb, $request->get('p', 1), $buyModel->getPerPage(), [
            'wrap-queries' => true
        ]);

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'form' => $form->createView(),
            'results' => $results
        ), $response);
    }

}