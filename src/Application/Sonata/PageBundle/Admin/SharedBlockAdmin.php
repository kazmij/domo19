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

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\PageBundle\Entity\BaseBlock;
use Sonata\PageBundle\Admin\SharedBlockAdmin as BaseSharedBlockAdmin;

/**
 * Admin class for shared Block model.
 *
 * @author Romain Mouillard <romain.mouillard@gmail.com>
 */
class SharedBlockAdmin extends BaseSharedBlockAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var BaseBlock $block */
        $block = $this->getSubject();

        // New block
        if ($block && null === $block->getId()) {
            $block->setType($this->request->get('type'));
        }

        $formMapper
            ->with('form.field_group_general')
            ->add('name', null, ['required' => true])
            ->add('enabled')
            ->end();

        $formMapper->with('form.field_group_options');

        /** @var BaseBlockService $service */
        if($block) {
            $service = $this->blockManager->get($block);
        }

        if($block) {
            if ($block->getId() > 0) {
                $service->buildEditForm($formMapper, $block);
            } else {
                $service->buildCreateForm($formMapper, $block);
            }
        }

        $formMapper->end();

        $formMapper
            ->with('form.field_group_general')
            ->add('alias')
            ->end();
    }
}
