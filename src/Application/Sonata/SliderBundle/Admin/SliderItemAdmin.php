<?php

namespace Application\Sonata\SliderBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;


class SliderItemAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    ];


    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->add('move', $this->getRouterIdParameter() . '/move/{position}');
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->parentAssociationMapping = 'slider';
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('url')
            ->add('title')
            ->add('lead')
            ->add('position')
            ->add('startDate')
            ->add('endDate')
            ->add('enabled');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('image', null, ['template' => 'SonataMediaBundle:MediaAdmin:list_image.html.twig'])
            ->addIdentifier('title')
            ->add('url')
            ->add('lead')
            ->add('position')
            ->add('startDate')
            ->add('endDate')
            ->add('enabled')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'move' => [
                        'template' => '@PixSortableBehavior/Default/_sort.html.twig'
                    ],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $now = new \DateTime();

        $formMapper
            ->add('title')
            ->add('url')
            ->add('lead')
            ->add('position')
            ->add('image', ModelListType::class, ['required' => false], [
                'link_parameters' => [
                    'hide_context' => false,
                ]
            ])
            ->add('startDate', 'sonata_type_datetime_picker', [
                'required' => false,
                'dp_min_date' => $now->format('d.m.Y H:i'),
                'dp_language' => 'pl',
                'translation_domain' => $this->getTranslationDomain(),
                'format' => "dd.MM.yyyy, HH:mm", // for mapping
                'attr' => [
                    'data-date-format' => 'DD.MM.YYYY, HH:mm',
                    'locale' => 'pl', // moment.js
                    'format' => 'nominative' // moment.js
                ]
            ])
            ->add('endDate', 'sonata_type_datetime_picker', [
                'required' => false,
                'dp_min_date' => $now->format('d.m.Y H:i'),
                'dp_language' => 'pl',
                'translation_domain' => $this->getTranslationDomain(),
                'format' => "dd.MM.yyyy, HH:mm", // for mapping
                'attr' => [
                    'data-date-format' => 'DD.MM.YYYY, HH:mm',
                    'locale' => 'pl', // moment.js
                    'format' => 'nominative' // moment.js
                ]
            ])
            ->add('enabled');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('url')
            ->add('title')
            ->add('lead')
            ->add('position')
            ->add('startDate')
            ->add('endDate')
            ->add('enabled');
    }
}
