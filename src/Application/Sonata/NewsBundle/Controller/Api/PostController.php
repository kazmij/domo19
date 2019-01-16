<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Controller\Api;

use Application\Sonata\NewsBundle\Entity\Post;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sonata\DatagridBundle\Pager\PagerInterface;
use Sonata\FormatterBundle\Formatter\Pool as FormatterPool;
use Sonata\NewsBundle\Mailer\MailerInterface;
use Sonata\NewsBundle\Model\Comment;
use Sonata\NewsBundle\Model\CommentManagerInterface;
use Sonata\NewsBundle\Model\PostManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\NewsBundle\Controller\Api\PostController as BasePostController;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
class PostController extends BasePostController
{

    /**
     * Retrieves the list of posts (paginated) based on criteria.
     *
     * @ApiDoc(
     *  resource=true,
     *  output={"class"="Sonata\DatagridBundle\Pager\PagerInterface", "groups"={"sonata_api_read"}}
     * )
     *
     * @REST\QueryParam(name="page", requirements="\d+", default="1", description="Page for posts list pagination")
     * @REST\QueryParam(name="count", requirements="\d+", default="10", description="Number of posts by page")
     * @REST\QueryParam(name="enabled", requirements="0|1", nullable=true, strict=true, description="Enabled/Disabled posts filter")
     * @REST\QueryParam(name="dateQuery", requirements=">|<|=", default=">", description="Date filter orientation (>, < or =)")
     * @REST\QueryParam(name="dateValue", requirements="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-2][0-9]:[0-5][0-9]:[0-5][0-9]([+-][0-9]{2}(:)?[0-9]{2})?", nullable=true, strict=true, description="Date filter value")
     * @REST\QueryParam(name="tag", requirements="\S+", nullable=true, strict=true, description="Tag name filter")
     * @REST\QueryParam(name="author", requirements="\S+", nullable=true, strict=true, description="Author filter")
     * @REST\QueryParam(name="mode", requirements="public|admin", default="public", description="'public' mode filters posts having enabled tags and author")
     * @REST\QueryParam(name="category", requirements="\d+", nullable=true, description="Category id")
     *
     * @REST\View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return PagerInterface
     */
    public function getPostsAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $count = $paramFetcher->get('count');

        $pager = $this->postManager->getPager($this->filterCriteria($paramFetcher), $page, $count);

        return $pager;
    }

    /**
     * Write a post, this method is used by both POST and PUT action methods.
     *
     * @param Request $request Symfony request
     * @param int|null $id A post identifier
     *
     * @return FormInterface
     */
    protected function handleWritePost($request, $id = null)
    {
        $post = $id ? $this->getPost($id) : $this->postManager->create();

        $method = $post->getId() ? 'PUT' : 'POST';

        $formOptions = [
            'csrf_protection' => false,
            'method' => $method
        ];

        $form = $this->formFactory->createNamed(null, 'sonata_news_api_form_post', $post, $formOptions);

        if($post->getId()) {
            $formDataKeys = array_keys($request->request->all());
            foreach ($form->all() as $child) {
                if(!in_array($child->getName(), $formDataKeys)) {
                    $form->remove($child->getName());
                }
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $post = $form->getData();
            $post->setContent($this->formatterPool->transform($post->getContentFormatter(), $post->getRawContent()));
            $this->postManager->save($post);

            $context = new Context();
            $context->setGroups(['sonata_api_read']);

            // simplify when dropping FOSRest < 2.1
            if (method_exists($context, 'enableMaxDepth')) {
                $context->enableMaxDepth();
            } else {
                $context->setMaxDepth(10);
            }

            $view = View::create($post);
            $view->setContext($context);

            return $view;
        } else {

        }

        return $form;
    }
}
