<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\ClassificationBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Valid;
use Sonata\ClassificationBundle\Admin\CategoryAdmin as BaseCategoryAdmin;

class CategoryAdmin extends BaseCategoryAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('General')
            ->add('customKey')
            ->add('customName')
            ->add('icon')
            ->end();

        if (interface_exists(MediaInterface::class)) {
            $formMapper
                ->with('General')
                ->add('media', ModelListType::class, [
                    'required' => false,
                ], [
                    'link_parameters' => [
                        'provider' => 'sonata.media.provider.image',
                        'context' => 'default',
                    ],
                ])
                ->end();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        parent::configureDatagridFilters($datagridMapper);

        $datagridMapper->add('customKey');
        $datagridMapper->add('customName');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('customKey');
        $listMapper->add('customName');
    }
}
