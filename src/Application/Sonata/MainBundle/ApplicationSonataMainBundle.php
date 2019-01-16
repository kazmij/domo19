<?php

namespace Application\Sonata\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sonata\CoreBundle\Form\FormHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApplicationSonataMainBundle extends Bundle
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
            'sonata_main_api_form_mail' => 'Application\Sonata\MainBundle\Form\Type\ApiMailType',
            'sonata_main_api_form_apply' => 'Application\Sonata\MainBundle\Form\Type\ApplyMailType',
            'sonata_main_block_buy_form' => 'Application\Sonata\MainBundle\Form\Type\BuyType',
            'sonata_main_block_buy_mini_form' => 'Application\Sonata\MainBundle\Form\Type\SearchType',
        ]);
    }

}
