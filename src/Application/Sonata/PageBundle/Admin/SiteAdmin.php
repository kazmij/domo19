<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\BlockBundle\Block\BlockServiceInterface;
use Sonata\BlockBundle\Form\Type\ServiceListType;
use Sonata\PageBundle\Model\PageInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sonata\PageBundle\Admin\SiteAdmin as BaseSiteAdmin;
use Sonata\BlockBundle\Model\BaseBlock;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Admin class for the Site model.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SiteAdmin extends BaseSiteAdmin
{
    const MENU_CONTEXT = 'menu';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form_site.label_codes', ['class' => 'col-md-6'])
            ->add('title', null, ['required' => false])
            ->add('headCode', TextareaType::class, ['required' => false])
            ->add('footerCode', TextareaType::class, ['required' => false])
            ->add('bodyCode', TextareaType::class, ['required' => false])
            ->end();
    }
}
