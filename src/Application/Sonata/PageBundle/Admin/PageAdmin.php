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
use Sonata\PageBundle\Admin\PageAdmin as BasePageAdmin;
use Sonata\BlockBundle\Model\BaseBlock;

/**
 * Admin class for the Block model.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageAdmin extends BasePageAdmin
{
    const MENU_CONTEXT = 'menu';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('form_page.group_main_label')
            ->add('menus', 'sonata_type_model', [
                'multiple'      => true,
                'required'      => false,
                'query'         => $this->getCollectionChoices(self::MENU_CONTEXT),
                'class'         => 'ApplicationSonataClassificationBundle:Collection',
                'model_manager' => $this->getModelManager(),
            ])
            ->end();
    }

    /**
     * Category choices filtered by context
     *
     * @return array
     */
    protected function getCollectionChoices($context)
    {
        $eM = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');

        /* @var $repo \Doctrine\ORM\EntityRepository */
        $repo = $eM->getRepository('Application\Sonata\ClassificationBundle\Entity\Collection');

        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $repo->createQueryBuilder('c')
            ->where('c.context = :context')
            ->orderBy('c.id', 'ASC')
            ->setParameter(':context', $context);

        return $qb;
    }
}
