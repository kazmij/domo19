<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Admin;

use Application\Sonata\ClassificationBundle\Entity\Category;
use Application\Sonata\NewsBundle\Entity\Post;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\NewsBundle\Admin\PostAdmin as BasePostAdmin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;
use Sonata\NewsBundle\Form\Type\CommentStatusType;
use Sonata\NewsBundle\Model\CommentInterface;
use Sonata\NewsBundle\Permalink\PermalinkInterface;
use Sonata\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostAdmin extends BasePostAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('author')
            ->add('enabled')
            ->add('title')
            ->add('category')
            ->add('abstract')
            ->add('content', null, ['safe' => true])
            ->add('tags')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $container = $this->getConfigurationPool()->getContainer();
        $newsContext = $container->get('sonata.classification.manager.context')->find(Post::$NEWS_CONTEXT);
        $mainCategory = $container->get('sonata.classification.manager.category')->getRootCategory(Post::$NEWS_CONTEXT);

        $isHorizontal = 'horizontal' == $this->getConfigurationPool()->getOption('form_type');
        $formMapper
            ->with('group_post', [
                    'class' => 'col-md-8',
                ])
                ->add('author', ModelListType::class)
                ->add('title')
                ->add('abstract', TextareaType::class, [
                    'attr' => ['rows' => 5],
                ])
                ->add('content', FormatterType::class, [
                    'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                    'format_field' => 'contentFormatter',
                    'source_field' => 'rawContent',
                    'source_field_options' => [
                        'horizontal_input_wrapper_class' => $isHorizontal ? 'col-lg-12' : '',
                        'attr' => ['class' => $isHorizontal ? 'span10 col-sm-10 col-md-10' : '', 'rows' => 20],
                    ],
                    'ckeditor_context' => 'news',
                    'target_field' => 'content',
                    'listener' => true,
                ])
            ->end()
            ->with('group_status', [
                    'class' => 'col-md-4',
                ])
                ->add('enabled', CheckboxType::class, ['required' => false])
                ->add('gallery', ModelListType::class, ['required' => false], [
                    'link_parameters' => [
                        'context' => 'news',
                        'hide_context' => true,
                    ],$mainCategory
                ])

                ->add('publicationDateStart', DateTimePickerType::class, [
                    'dp_side_by_side' => true,
                ])
                ->add('commentsCloseAt', DateTimePickerType::class, [
                    'dp_side_by_side' => true,
                    'required' => false,
                ])
                ->add('commentsEnabled', CheckboxType::class, [
                    'required' => false,
                ])
                ->add('commentsDefaultStatus', CommentStatusType::class, [
                    'expanded' => true,
                ])
            ->end()

            ->with('group_classification', [
                'class' => 'col-md-4',
                ])
                ->add('tags', ModelAutocompleteType::class, [
                    'property' => 'name',
                    'multiple' => 'true',
                    'required' => false,
                    'btn_add' => 'Add'
                ])

            ->add('category', CategorySelectorType::class, [
                'model_manager' =>  $this->getConfigurationPool()->getAdminByAdminCode('sonata.classification.admin.category')->getModelManager(),
                'class' => 'Application\Sonata\ClassificationBundle\Entity\Category',
                'required' => true,
                'category' => $mainCategory ? $mainCategory : new Category(),
                'context' =>  $newsContext ? $newsContext : null
            ])
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('category')
            ->add('commentsEnabled', null, ['editable' => true])
            ->add('publicationDateStart')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $that = $this;

        $datagridMapper
            ->add('title')
            ->add('enabled')
            ->add('tags', null, ['field_options' => ['expanded' => true, 'multiple' => true]])
            ->add('author')
            ->add('with_open_comments', CallbackFilter::class, [
//                'callback'   => array($this, 'getWithOpenCommentFilter'),
                'callback' => function ($queryBuilder, $alias, $field, $data) use ($that) {
                    if (!is_array($data) || !$data['value']) {
                        return;
                    }

                    $queryBuilder->leftJoin(sprintf('%s.comments', $alias), 'c');
                    $queryBuilder->andWhere('c.status = :status');
                    $queryBuilder->setParameter('status', CommentInterface::STATUS_MODERATE);
                },
                'field_type' => CheckboxType::class,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            $this->trans('sidemenu.link_edit_post'),
            ['uri' => $admin->generateUrl('edit', ['id' => $id])]
        );

        $menu->addChild(
            $this->trans('sidemenu.link_view_comments'),
            ['uri' => $admin->generateUrl('sonata.news.admin.comment.list', ['id' => $id])]
        );

        if ($this->hasSubject() && null !== $this->getSubject()->getId()) {
            $menu->addChild(
                'sidemenu.link_view_post',
                ['uri' => $admin->getRouteGenerator()->generate(
                    'sonata_news_view',
                    ['permalink' => $this->permalinkGenerator->generate($this->getSubject())]
                )]
            );
        }
    }
}
