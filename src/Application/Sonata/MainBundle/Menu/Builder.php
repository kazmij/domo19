<?php

namespace Application\Sonata\MainBundle\Menu;

use Application\Sonata\ClassificationBundle\Entity\Collection;
use Application\Sonata\OfferBundle\Entity\Offer;
use Application\Sonata\PageBundle\Admin\PageAdmin;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sonata\PageBundle\Entity\PageManager;
use Application\Sonata\PageBundle\Entity\Page;


class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $_mainIndex = 0;

    /**
     * @param $name
     * @return int
     * @throws \Exception
     */
    private function getMenuId($name)
    {
        $repo = $this->getObjectManager()->getRepository('ApplicationSonataClassificationBundle:Collection');
        $collection = $repo->findOneBy([
            'context' => PageAdmin::MENU_CONTEXT,
            'name' => $name
        ]);

        if (is_null($collection)) {
            throw new \Exception("Menu " . $name . " doesn't exist");
        }

        return $collection->getId();
    }

    /**
     * @param $menuId
     * @return array
     */
    private function getPageIdsByMenuId($menuId)
    {
        $em = $this->container->get('doctrine')->getManager();

        /**
         * @var $repo EntityRepository
         */
        $repo = $em->getRepository('ApplicationSonataPageBundle:Page');

        $queryBuilder = $repo->createQueryBuilder('p');

        $result = $queryBuilder->innerJoin('p.menus', 'm', 'WITH', 'm.id = :menu_id')
            ->setParameter('menu_id', $menuId)
            ->getQuery()
            ->useResultCache(true)
            ->setCacheRegion('one_hour')
            ->getResult();

        $pageIds = [];
        foreach ($result as $k => $page) {
            $pageIds[] = $page->getId();
        }

        return $pageIds;
    }

    private function _prepareMenu($menuName, FactoryInterface $factory, $options)
    {
        if (isset($options['mobile'])) {
            //die('gfgf');
        }
        $menu = $factory->createItem('root_' . $menuName, $options);
        $this->_mainIndex = 1;

        if (isset($options['listAttributes'])) {
            $menu->setAttribute('listAttributes', $options['listAttributes']);
        }

        if (isset($options['linkAttributes'])) {
            $menu->setAttribute('linkAttributes', $options['linkAttributes']);
        }

        // add pages
        $mainPage = $this->getPageManager()->getPageByUrl($this->getSite(), '/');

        if ($mainPage->getChildren()) {
            $menuId = $this->getMenuId($menuName);
            $pageIds = $this->getPageIdsByMenuId($menuId);

            $this->addPage($mainPage, $menu, true, []);

            $count = $mainPage->getChildren()->count();
            foreach ($mainPage->getChildren() as $key => $page) {
                if ($key == $count - 1 && isset($options['mobile'])) {
                    $menu->addChild('<span>Rodzaje projektów</span>', [
                        'attributes' => [
                            'class' => 'with-submenu',
                            'data-submenu' => 'project-types'
                        ],
                        'linkAttributes' => [
                            'class' => ''
                        ],
                        'uri' => '#'
                    ])
                        ->setExtra('safe_label', true);

                    $menu->addChild('<span>Nasze usługi</span>', [
                        'attributes' => [
                            'class' => 'with-submenu',
                            'data-submenu' => 'our-services'
                        ],
                        'linkAttributes' => [
                            'class' => ''
                        ],
                        'uri' => '#'
                    ])
                        ->setExtra('safe_label', true);

                    $menu->addChild('<span>Warto wiedzieć</span>', [
                        'attributes' => [
                            'class' => 'with-submenu',
                            'data-submenu' => 'good-to-know'
                        ],
                        'linkAttributes' => [
                            'class' => ''
                        ],
                        'uri' => '#'
                    ])
                        ->setExtra('safe_label', true);
                }
                if (in_array($page->getId(), $pageIds)) {
                    $this->addPage($page, $menu, true, $pageIds);
                }

            }
        } else {

        }
        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function ourServicesMenu(FactoryInterface $factory, array $options)
    {
        $pageManager = $this->container->get('sonata.page.manager.page');
        $wortkKnowingPages = $pageManager->getEntityManager()->getRepository(Page::class)
            ->createQueryBuilder('p')
            ->join('p.menus', 'm')
            ->where('m.name = :name')
            ->setParameter('name', Collection::OUR_SERVICES)
            ->getQuery()
            ->setCacheable(true)
            ->setCacheRegion('one_hour')
            ->getResult();

        $menu = $factory->createItem(Collection::OUR_SERVICES, $options);

        if (isset($options['mobile'])) {
            $menu->addChild('<span>Powrót</span>', [
                'attributes' => [
                    'class' => 'go-back'
                ],
                'linkAttributes' => [
                    'class' => ''
                ],
                'uri' => '#'
            ])
                ->setExtra('safe_label', true);
        }

        foreach ($wortkKnowingPages as $page) {
            if ($page->getChildren()->count()) {
                $menu->addChild($page->getName(), [
                    'attributes' => [
                        'class' => 'hidden'
                    ],
                    'linkAttributes' => [
                        'class' => ''
                    ],
                    'uri' => '#'

                ])
                    ->setExtra('safe_label', true);
            } else {
                $menu->addChild($page->getName(), [
                    'attributes' => [
                        'class' => ''
                    ],
                    'linkAttributes' => [
                        'class' => ''
                    ],
                    'route' => $page->getRouteName(),
                    'routeParameters' => $page->getRouteParams()
                ])
                    ->setExtra('safe_label', true);
            }
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function bottomMenu(FactoryInterface $factory, array $options)
    {
        $pageManager = $this->container->get('sonata.page.manager.page');
        $footerPages = $pageManager->getEntityManager()->getRepository(Page::class)
            ->createQueryBuilder('p')
            ->join('p.menus', 'm')
            ->where('m.name = :name')
            ->setParameter('name', Collection::FOOTER_MENU)
            ->getQuery()
            ->setCacheable(true)
            ->setCacheRegion('one_hour')
            ->getResult();

        $menu = $factory->createItem(Collection::FOOTER_MENU, $options);

        foreach ($footerPages as $page) {
                $menu->addChild($page->getName(), [
                    'attributes' => [
                        'class' => ''
                    ],
                    'linkAttributes' => [
                        'class' => ''
                    ],
                    'route' => $page->getRouteName(),
                    'routeParameters' => $page->getRouteParams()
                ])
                    ->setExtra('safe_label', true);
            }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function worthKnowingMenu(FactoryInterface $factory, array $options)
    {
        $pageManager = $this->container->get('sonata.page.manager.page');
        $wortkKnowingPages = $pageManager->getEntityManager()->getRepository(Page::class)
            ->createQueryBuilder('p')
            ->join('p.menus', 'm')
            ->where('m.name = :name')
            ->setParameter('name', Collection::WORTH_KNOWING)
            ->getQuery()
            ->setCacheable(true)
            ->setCacheRegion('one_hour')
            ->getResult();

        $menu = $factory->createItem(Collection::WORTH_KNOWING, $options);

        if (isset($options['mobile'])) {
            $menu->addChild('<span>Powrót</span>', [
                'attributes' => [
                    'class' => 'go-back'
                ],
                'linkAttributes' => [
                    'class' => ''
                ],
                'uri' => '#'
            ])
                ->setExtra('safe_label', true);
        }

        foreach ($wortkKnowingPages as $page) {
            if ($page->getChildren()->count()) {
                $menu->addChild($page->getName(), [
                    'attributes' => [
                        'class' => 'hidden'
                    ],
                    'linkAttributes' => [
                        'class' => ''
                    ],
                    'uri' => '#'

                ])
                    ->setExtra('safe_label', true);
            } else {
                $menu->addChild($page->getName(), [
                    'attributes' => [
                        'class' => ''
                    ],
                    'linkAttributes' => [
                        'class' => ''
                    ],
                    'route' => $page->getRouteName(),
                    'routeParameters' => $page->getRouteParams()
                ])
                    ->setExtra('safe_label', true);
            }
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function topCategoriesMenu(FactoryInterface $factory, array $options)
    {
        $categoryManager = $this->container->get('sonata.classification.manager.category');
        $categories = $categoryManager->getCategories(Offer::CATEGORIES_CONTEXT);

        $menu = $factory->createItem(Offer::CATEGORIES_CONTEXT, $options);
        if (isset($options['mobile'])) {
            $menu->addChild('<span>Powrót</span>', [
                'attributes' => [
                    'class' => 'go-back'
                ],
                'linkAttributes' => [
                    'class' => ''
                ],
                'uri' => '#'
            ])
                ->setExtra('safe_label', true);
        }

        foreach ($categories as $category) if ($category->getEnabled()) {
            $menu->addChild($category->getName(), [
                'attributes' => [
                    'class' => ''
                ],
                'linkAttributes' => [
                    'class' => ''
                ],
                'route' => 'application_sonata_offer_list',
                'routeParameters' => [
                    'slug' => $category->getSlug()
                ]

            ])
                ->setExtra('safe_label', true);
        }

        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $options['childrenAttributes'] = [
            'class' => 'desktop-menu-main'
        ];

        $options['listAttributes'] = [
            'class' => ''
        ];

        $options['linkAttributes'] = [
            'class' => 'n'
        ];

        $menu = $this->_prepareMenu('mainMenu', $factory, $options);

        return $menu;
    }

    protected function addPage(Page $page, ItemInterface $node, $countIndex = false, $pageIds)
    {
        //&& $page->getType() == 'sonata.page.service.default'
        if ($page->getEnabled()) {

            $url = $page->getCustomUrl() ?: $page->getUrl();
            $index = $countIndex ? sprintf(' el-%s ', $this->_mainIndex++) : ' ';

            $request = $this->container->get('request_stack')->getCurrentRequest();

            $pathInfo = $request->getPathInfo();

            $customCls = '';
            if ($url == $pathInfo) {
                $customCls = 'active';
                $node->setCurrent(true);
            }

            $pageHasChild = false;
            foreach ($page->getChildren() as $subPage) {
                if (in_array($subPage->getId(), $pageIds)) {
                    $pageHasChild = true;
                    break;
                }
            }

            if ($pageHasChild === false) {
                $menuItemCls = ' ';
                $childrenAttributesCls = ' ';
                $caret = false;
            } else {
                $menuItemCls = 'main-header-menu-dropdown';
                $childrenAttributesCls = 'dropdown';
                $caret = 'button';
            }

            $linkConfig = [
                'attributes' => ['class' => $page->getSlug() . $index . ($page->hasChildren() ? $menuItemCls : '') . ' ' . $customCls],
                'extras' => ['description' => $page->getName(), 'caret' => $caret],
                'childrenAttributes' => $page->hasChildren() ? ['class' => $childrenAttributesCls . ' aaaaa', 'role' => 'menu'] : []
            ];

            $nodeAttributes = $node->getAttributes();

            if (isset($nodeAttributes['listAttributes'])) {
                $linkConfig['attributes'] = array_merge($linkConfig['attributes'], $nodeAttributes['listAttributes']);
            }

            if ($page->getCustomUrl() != '') {
                $pageNode = $node->addChild($page->getName(), array_merge([
                    'uri' => $url,
                ], $linkConfig));
            } else {
                if ($page->getRouteName() == 'page_slug') {
                    $routeParameters = ['path' => $url];
                } else {
                    $routeParameters = [];
                }
                $pageNode = $node->addChild($page->getName(), array_merge([
                    'route' => $page->getRouteName(),
                    'routeParameters' => $routeParameters
                ], $linkConfig));
            }

            if (isset($nodeAttributes['linkAttributes'])) {
                $pageNode->setLinkAttributes($nodeAttributes['linkAttributes']);
            }

            if ($page->hasChildren()) {
                foreach ($page->getChildren() as $key => $subPage) {
                    if (in_array($subPage->getId(), $pageIds)) {
                        if ($key == 0) {
                            $pageNode->addChild('Wróć', [
                                'uri' => '#',
                                'attributes' => ['class' => 'go-back']
                            ]);
                        }
                        $this->addPage($subPage, $pageNode, false, $pageIds);
                    }
                }
            }
        }
    }

    /**
     * Add "more menu" for tablets
     *
     * @param type $menu
     * @param int $offset
     */
    protected function addMoreMenu($menu, $offset = 4)
    {
        $newMenu = $menu->copy();

        $moreNode = $menu->addChild('More', [
            'uri' => '#',
            'extras' => ['caret' => true],
            'attributes' => ['class' => 'dropdown more-icon-menu'],
            'linkAttributes' => ['class' => 'dropdown-toggle'],
            'childrenAttributes' => ['class' => 'dropdown-menu', 'role' => 'menu'],
        ]);

        $moreNode->setChildren(array_slice($newMenu->getChildren(), $offset));
    }

    /**
     * @return \Application\Sonata\PageBundle\Entity\Site
     * @throws \Exception
     */
    protected function getSite()
    {
        $http_host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/\:[0-9]+/', '', $_SERVER['HTTP_HOST']) : 'localhost';

        $repo = $this->getObjectManager()->getRepository('Application\Sonata\PageBundle\Entity\Site');
        $site = $repo->findOneBy(['host' => [$http_host, 'localhost']]);

        if (!$site) {
            throw new \Exception("Site doesn't exist");
        }

        return $site;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getObjectManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return PageManager
     */
    protected function getPageManager()
    {
        return $this->container->get('sonata.page.manager.page');
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return ItemInterface
     */
    public function footerMenu(FactoryInterface $factory, array $options)
    {
        return $this->_prepareMenu('footerMenu', $factory, $options);
    }

}