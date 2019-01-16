<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Entity;

use Sonata\BlockBundle\Model\BlockManagerInterface;
use Sonata\CoreBundle\Model\BaseEntityManager;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Sonata\PageBundle\Entity\BlockManager as BaseBlockManager;

/**
 * This class manages BlockInterface persistency with the Doctrine ORM.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class BlockManager extends BaseBlockManager implements BlockManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function save($block, $andFlush = true)
    {
        if(!$block->getAlias()) {
            $block->setAlias(\Sonata\PageBundle\Model\Page::slugify($block->getName()));
        }
        parent::save($block, $andFlush);

        return $block;
    }
}
