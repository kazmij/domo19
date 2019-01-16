<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Model\BlockManagerInterface;
use Sonata\DatagridBundle\Pager\PagerInterface;
use Sonata\NotificationBundle\Backend\BackendInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\PageManagerInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Get;
use Sonata\PageBundle\Controller\Api\PageController as BasePageController;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
class PageController extends BasePageController
{

    /**
     * Retrieves a specific page.
     *
     * @Get("/pages/slug/{slug}")
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="slug", "dataType"="string", "requirement"="[a-z]+\w*", "description"="page slug"}
     *  },
     *  output={"class"="Sonata\PageBundle\Model\PageInterface", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when page is not found"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return PageInterface
     */
    public function getPageBySlugAction($slug)
    {
        $page = $this->pageManager->findOneBy(['slug' => $slug]);

        if (null === $page) {
            throw new NotFoundHttpException(sprintf('Page with slug (%s) not found', $slug));
        }

        return $page;
    }

    /**
     * Retrieves a specific page's blocks.
     *
     * @Get("/pages/{slug}/get/blocks")
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="slug", "dataType"="string", "requirement"="[a-z]+\w*", "description"="page slug"}
     *  },
     *  output={"class"="Sonata\BlockBundle\Model\BlockInterface", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when page is not found"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return BlockInterface[]
     */
    public function getPageBlocksBySlugAction($slug)
    {
        $page = $this->pageManager->findOneBy(['slug' => $slug]);

        if(!$page) {
            $page = $this->pageManager->findOneBy(['name' => $slug]);
        }

        if (null === $page) {
            throw new NotFoundHttpException(sprintf('Page with slug (%s) not found', $slug));
        }

        return $page->getBlocks();
    }

    /**
     * Write a page, this method is used by both POST and PUT action methods.
     *
     * @param Request  $request Symfony request
     * @param int|null $id      A page identifier
     *
     * @return FormInterface
     */
    protected function handleWritePage($request, $id = null)
    {
        $page = $id ? $this->getPage($id) : $this->pageManager->create();

        $method = $page->getId() ? 'PUT' : 'POST';

        $formOptions = [
            'csrf_protection' => false,
            'method' => $method,
            'allow_extra_fields' => true
        ];

        $form = $this->formFactory->createNamed(null, 'sonata_page_api_form_page', $page, $formOptions);
        if($page->getId()) {
            $formDataKeys = array_keys($request->request->all());
            foreach ($form->all() as $child) {
                if(!in_array($child->getName(), $formDataKeys)) {
                    $form->remove($child->getName());
                }
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $page = $form->getData();
            $this->pageManager->save($page);

            return $this->serializeContext($page, ['sonata_api_read']);
        }

        return $form;
    }

}
