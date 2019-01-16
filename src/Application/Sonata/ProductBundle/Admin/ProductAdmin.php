<?php

namespace Application\Sonata\ProductBundle\Admin;

use Application\Sonata\ProductBundle\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\AdminBundle\Form\Type\ModelListType;

class ProductAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('subtitle')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('subtitle')
            ->add('enabled')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $isHorizontal = false;

        $container = $this->getConfigurationPool()->getContainer();
        $newsContext = $container->get('sonata.classification.manager.context')->find(Product::$PRODUCT_CONTEXT);
        $mainCategory = $container->get('sonata.classification.manager.category')->getRootCategory(Product::$PRODUCT_CONTEXT);

        $formMapper
            ->add('title')
            ->add('subtitle')
            ->add('description', FormatterType::class, [
                'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                'format_field' => 'contentFormatter',
                'source_field' => 'rawContent',
                'source_field_options' => [
                    'horizontal_input_wrapper_class' => $isHorizontal ? 'col-lg-12' : '',
                    'attr' => ['class' => $isHorizontal ? 'span10 col-sm-10 col-md-10' : '', 'rows' => 20],
                ],
                'ckeditor_context' => 'default',
                'target_field' => 'description',
                'listener' => true,
            ])
            ->add('gallery', ModelListType::class, ['required' => false], [
                'link_parameters' => [
                    'context' => 'product',
                    'hide_context' => true,
                ],$mainCategory
            ])
            ->add('enabled')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('subtitle')
            ->add('enabled')
        ;
    }
}
