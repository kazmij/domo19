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
use Sonata\PageBundle\Admin\BlockAdmin as BaseBlockAdmin;
use Sonata\BlockBundle\Model\BaseBlock;

/**
 * Admin class for the Block model.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class BlockAdmin extends BaseBlockAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form.field_group_options')
            ->add('alias')
            ->end();
    }

    /**
     * {@inheritdoc}
     *
     * @param BaseBlock $object
     */
    public function preUpdate($object)
    {

//        if(!$object->getAlias()) {
//            $object->setAlias(\Sonata\PageBundle\Model\Page::slugify($object->getName()));
//        }

        parent::preUpdate($object);
    }

    /**
     * {@inheritdoc}
     *
     * @param BaseBlock $object
     */
    public function prePersist($object)
    {

//        if(!$object->getAlias()) {
//            $object->setAlias(\Sonata\PageBundle\Model\Page::slugify($object->getName()));
//        }

        parent::prePersist($object);
    }
}
