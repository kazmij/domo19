<?php

namespace Application\Sonata\OfferBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sonata\CoreBundle\Form\FormHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApplicationSonataOfferBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->registerFormMapping();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerFormMapping();
    }

    /**
     * Register form mapping information.
     */
    public function registerFormMapping()
    {
        FormHelper::registerFormTypeMapping([
            'sonata_main_offer_form_mail' => 'Application\Sonata\OfferBundle\Form\Type\MailType',
        ]);
    }

}
