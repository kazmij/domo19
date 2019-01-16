<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\SliderBundle\Controller\Api;

use Application\Sonata\NewsBundle\Entity\Post;
use Application\Sonata\SliderBundle\Entity\SliderManager;
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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SliderController
{

    /**
     * @var SliderManager
     */
    protected $sliderManager;

    /**
     * @param SliderManager    $sliderManager
     */
    public function __construct(SliderManager $sliderManager)
    {
        $this->sliderManager = $sliderManager;
    }

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
     *
     * @REST\View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return PagerInterface
     */
    public function getSlidersAction(ParamFetcherInterface $paramFetcher)
    {
        $page = $paramFetcher->get('page');
        $count = $paramFetcher->get('count');

        $pager = $this->sliderManager->getPager($this->filterCriteria($paramFetcher), $page, $count);

        return $pager;
    }

    /**
     * Retrieves a specific post.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="post id"}
     *  },
     *  output={"class"="sonata_news_api_form_post", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when post is not found"
     *  }
     * )
     *
     * @REST\View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     *
     * @param int $id A post identifier
     *
     * @return Post
     */
    public function getSliderAction($id)
    {
        return $this->getSlider($id);
    }


    /**
     * Filters criteria from $paramFetcher to be compatible with the Pager criteria.
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return array The filtered criteria
     */
    protected function filterCriteria(ParamFetcherInterface $paramFetcher)
    {
        $criteria = $paramFetcher->all();

        unset($criteria['page'], $criteria['count']);

        foreach ($criteria as $key => $value) {
            if (null === $value) {
                unset($criteria[$key]);
            }
        }

        if (array_key_exists('dateValue', $criteria)) {
            $date = new \DateTime($criteria['dateValue']);
            $criteria['date'] = [
                'query' => sprintf('p.createdAt %s :dateValue', $criteria['dateQuery']),
                'params' => ['dateValue' => $date],
            ];
            unset($criteria['dateValue'], $criteria['dateQuery']);
        } else {
            unset($criteria['dateQuery']);
        }

        return $criteria;
    }

    /**
     * Retrieves post with id $id or throws an exception if it doesn't exist.
     *
     * @param int $id A Post identifier
     *
     * @throws NotFoundHttpException
     *
     * @return Post
     */
    protected function getSlider($id)
    {
        $slider = $this->sliderManager->find($id);

        if (null === $slider) {
            throw new NotFoundHttpException(sprintf('Post (%d) not found', $id));
        }

        return $slider;
    }

}
