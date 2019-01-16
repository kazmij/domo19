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

use Doctrine\Common\Persistence\ManagerRegistry;
use Sonata\CoreBundle\Model\BaseEntityManager;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Sonata\PageBundle\Model\Page;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\PageManagerInterface;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Entity\PageManager as BasePageManager;

/**
 * This class manages PageInterface persistency with the Doctrine ORM.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageManager extends BasePageManager implements PageManagerInterface
{

    /**
     * {@inheritdoc}
     */
    public function fixUrl(PageInterface $page)
    {
        if ($page->isInternal()) {
            $page->setUrl(null); // internal routes do not have any url ...

            return;
        }

        // hybrid page cannot be altered
        if (!$page->isHybrid()) {
            // make sure Page has a valid url
            if ($page->getParent()) {
                if (!$page->getSlug()) {
                    $page->setSlug(Page::slugify($page->getName()));
                }

                if ('/' == $page->getParent()->getUrl()) {
                    $base = '/';
                } elseif ('/' != substr($page->getParent()->getUrl(), -1)) {
                    $base = $page->getParent()->getUrl().'/';
                } else {
                    $base = $page->getParent()->getUrl();
                }

                $page->setUrl($base.$page->getSlug());
            } else {
                //Commented due to API
                // a parent page does not have any slug - can have a custom url ...
                //$page->setSlug(null);
                $page->setUrl('/'.$page->getSlug());
            }
        }

        foreach ($page->getChildren() as $child) {
            $this->fixUrl($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPager(array $criteria, $page, $limit = 10, array $sort = [])
    {
        $query = $this->getRepository()
            ->createQueryBuilder('p')
            ->leftJoin('p.blocks', 'b');

        $fields = $this->getEntityManager()->getClassMetadata($this->class)->getFieldNames();

        foreach ($sort as $field => $direction) {
            if (!in_array($field, $fields)) {
                throw new \RuntimeException(sprintf("Invalid sort field '%s' in '%s' class", $field, $this->class));
            }
        }
        if (0 == count($sort)) {
            $sort = ['name' => 'ASC'];
        }
        foreach ($sort as $field => $direction) {
            $query->orderBy(sprintf('p.%s', $field), strtoupper($direction));
        }

        $parameters = [];

        if (isset($criteria['enabled'])) {
            $query->andWhere('p.enabled = :enabled');
            $parameters['enabled'] = $criteria['enabled'];
        }

        if (isset($criteria['edited'])) {
            $query->andWhere('p.edited = :edited');
            $parameters['edited'] = $criteria['edited'];
        }

        if (isset($criteria['site'])) {
            $query->join('p.site', 's');
            $query->andWhere('s.id = :siteId');
            $parameters['siteId'] = $criteria['site'];
        }

        if (isset($criteria['parent'])) {
            $query->join('p.parent', 'pa');
            $query->andWhere('pa.id = :parentId');
            $parameters['parentId'] = $criteria['parent'];
        }

        if (isset($criteria['root'])) {
            $isRoot = (bool) $criteria['root'];
            if ($isRoot) {
                $query->andWhere('p.parent IS NULL');
            } else {
                $query->andWhere('p.parent IS NOT NULL');
            }
        }

        $query->setParameters($parameters);

        $pager = new Pager();
        $pager->setMaxPerPage($limit);
        $pager->setQuery(new ProxyQuery($query));
        $pager->setPage($page);
        $pager->init();

        return $pager;
    }
}
