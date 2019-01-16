<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Controller\Api;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FOSRestView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sonata\DatagridBundle\Pager\PagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Sonata\MediaBundle\Provider\Pool;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\MediaBundle\Controller\Api\MediaController as BaseMediaController;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * Note: Media is plural, medium is singular (at least according to FOSRestBundle route generator).
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class MediaController extends BaseMediaController
{

    /**
     * Constructor.
     *
     * @param MediaManagerInterface $mediaManager
     * @param Pool $mediaPool
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(MediaManagerInterface $mediaManager, Pool $mediaPool, FormFactoryInterface $formFactory, ContainerInterface $container)
    {
        parent::__construct($mediaManager, $mediaPool, $formFactory);

        $this->container = $container;
    }

    /**
     * Retrieves a specific media.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="media id"}
     *  },
     *  output={"class"="Application\Sonata\MediaBundle\Entity\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when media is not found"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return MediaInterface
     */
    public function getMediumAction($id)
    {
        return $this->getMedium($id);
    }


    /**
     * Updates a medium
     * If you need to upload a file (depends on the provider) you will need to do so by sending content as a multipart/form-data HTTP Request
     * See documentation for more details.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="medium identifier"}
     *  },
     *  input={"class"="sonata_media_api_form_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Sonata\MediaBundle\Model\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while medium update",
     *      404="Returned when unable to find medium"
     *  }
     * )
     *
     * @param int $id A Medium identifier
     * @param Request $request A Symfony request
     *
     * @throws NotFoundHttpException
     *
     * @return MediaInterface
     */
    public function putMediumAction($id, Request $request)
    {
        $medium = $this->getMedium($id);

        try {
            $provider = $this->mediaPool->getProvider($medium->getProviderName());
        } catch (\RuntimeException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        } catch (\InvalidArgumentException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }

        return $this->handleWriteMedium($request, $medium, $provider);
    }

    /**
     * Adds a medium of given provider
     * If you need to upload a file (depends on the provider) you will need to do so by sending content as a multipart/form-data HTTP Request
     * See documentation for more details.
     *
     * @ApiDoc(
     *  resource=true,
     *  input={"class"="sonata_media_api_form_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Application\Sonata\MediaBundle\Entity\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while medium creation",
     *      404="Returned when unable to find medium"
     *  }
     * )
     *
     * @Route(requirements={"provider"="[A-Za-z0-9.]*"})
     *
     * @param string $provider A media provider
     * @param Request $request A Symfony request
     *
     * @throws NotFoundHttpException
     *
     * @return MediaInterface
     */
    public function postProviderMediumAction($provider, Request $request)
    {
        $medium = $this->mediaManager->create();
        $medium->setProviderName($provider);

        try {
            $mediaProvider = $this->mediaPool->getProvider($provider);
        } catch (\RuntimeException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        } catch (\InvalidArgumentException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), $ex);
        }

        return $this->handleWriteMedium($request, $medium, $mediaProvider);
    }

    /**
     * Set Binary content for a specific media.
     *
     * @ApiDoc(
     *  input={"class"="Sonata\MediaBundle\Model\Media", "groups"={"sonata_api_write"}},
     *  output={"class"="Sonata\MediaBundle\Model\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when media is not found"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     * @param Request $request A Symfony request
     *
     * @throws NotFoundHttpException
     *
     * @return MediaInterface
     */
    public function putMediumBinaryContentAction($id, Request $request)
    {
        $media = $this->getMedium($id);

        $media->setBinaryContent($request);

        $this->mediaManager->save($media);

        return $media;
    }

    /**
     * Write a medium, this method is used by both POST and PUT action methods.
     *
     * @param Request $request
     * @param MediaInterface $media
     * @param MediaProviderInterface $provider
     *
     * @return View|FormInterface
     */
    protected function handleWriteMedium(Request $request, MediaInterface $media, MediaProviderInterface $provider)
    {
        $method = $media->getId() ? 'PUT' : 'POST';

        $formOptions = [
            'csrf_protection' => false,
            'method' => $method,
            'allow_extra_fields' => true,
            'provider_name' => $request->get('provider')
        ];

        $form = $this->formFactory->createNamed(null, 'sonata_media_api_form_media', $media, $formOptions);

        if ($media->getId()) {
            $formDataKeys = array_keys($request->request->all());
            foreach ($form->all() as $child) {
                if (!in_array($child->getName(), $formDataKeys)) {
                    $form->remove($child->getName());
                }
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
//            $oldMedia = $media;
//            $media = $form->getData();
//            $media->setCategory($oldMedia->getCategory());

            $this->mediaManager->save($media);

            $context = new Context();
            $context->setGroups(['sonata_api_read']);
            $context->enableMaxDepth();

            $baseUrl = $request->getSchemeAndHttpHost();// . $this->container->getParameter('base_assets_path');

            switch ($media->getProviderName()) {

                case 'sonata.media.provider.image':

                    $imageProvider = $this->container->get('sonata.media.provider.image');

                    $media->setFileOriginalUrl($baseUrl . $imageProvider->generatePublicUrl($media, "reference"));

                    break;

                case 'sonata.media.provider.file':
                    $fileProvider = $this->container->get('sonata.media.provider.file');

                    $fileUrl = $baseUrl . $fileProvider->generatePublicUrl($media, 'reference');

                    $media->setFileOriginalUrl($fileUrl);

                    break;
            }

            $view = FOSRestView::create($media);
            $view->setContext($context);

            return $view;
        }

        return $form;
    }

    /**
     * Retrieves a specific media images data
     *
     * @Get("/media/get-url/{id}/{filter}")
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="media id"},
     *      {"name"="filter", "dataType"="string", "requirement"="\w*", "description"="size filter name"}
     *  },
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when media is not found"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return MediaInterface
     */
    public function getMediumCustomAction(Request $request, $id, $filter = null)
    {
        $media = $this->getMedium($id);
        $baseUrl = $request->getSchemeAndHttpHost();// . $this->container->getParameter('base_assets_path');

        switch ($media->getProviderName()) {

            case 'sonata.media.provider.image':

                $imageProvider = $this->container->get('sonata.media.provider.image');
                $imageUrl = $imageProvider->generatePublicUrl($media, "reference");

                if (0 === strpos($imageUrl, $this->container->getParameter('base_assets_path'))) {
                    $imageUrl = str_replace($this->container->getParameter('base_assets_path'), '', $imageUrl);
                }

                $media->setFileOriginalUrl($baseUrl . $imageProvider->generatePublicUrl($media, "reference"));

                if ($filter) {
                    $imagine = $this->container->get('liip_imagine');
                    $filterManager = $this->container->get('liip_imagine.filter.manager');
                    $allFilters = $filterManager->getFilterConfiguration()->all();

                    if (isset($allFilters[$filter])) {

                        $dataManager = $this->container->get('liip_imagine.data.manager');
                        $image = $dataManager->find($filter, $imageUrl);
                        $image = $filterManager->applyFilter($image, $filter);

                        /** @var CacheManager */
                        $imagineCacheManager = $this->container->get('liip_imagine.cache.manager');
                        $imagineCacheManager->store($image, $imageUrl, $filter, 'default');

                        $url = $imagineCacheManager->resolve($imageUrl, $filter);

                        $url = str_replace('/media/cache', $this->container->getParameter('base_routes_prefix') . '/media/cache', $url);

                        $media->setFileUrl($url);
                    }
                }

                break;

            case 'sonata.media.provider.file':
                $fileProvider = $this->container->get('sonata.media.provider.file');

                $fileUrl = $baseUrl . $fileProvider->generatePublicUrl($media, 'reference');

                $media->setFileOriginalUrl($fileUrl);

                break;
        }


        return $media;
    }
}
