<?php

namespace Application\Sonata\PageBundle\Block;

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
 * Class TopWithForm
 */
class TopWithForm extends FormatterBlockService
{
    use ContainerAwareTrait;


    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);

        $resolver->setDefault('template', '@ApplicationSonataPage/Block/topWithForm.html.twig');
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();
        $formOptions = [
            'csrf_protection' => false,
            'method' => 'GET',
            'action' => $this->container->get('router')->generate('application_sonata_offer_list')
        ];
        $buyModel = new BuyModel();
        $form = $this->container->get('form.factory')->createNamed(null, 'sonata_main_block_buy_mini_form', $buyModel, $formOptions);

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'form' => $form->createView()
        ), $response);
    }

}