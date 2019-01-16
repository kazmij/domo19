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

use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Model\BlockManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\PageBundle\Controller\Api\BlockController as BaseBlockController;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class BlockController extends BaseBlockController
{

    /**
     * Retrieves a specific block.
     *
     * @Get("/blocks/slug/{slug}")
     *
     * @ApiDoc(
     *  resource=true,
     *  requirements={
     *      {"name"="slug", "dataType"="string", "requirement"="\w+", "description"="block alias"}
     *  },
     *  output={"class"="Sonata\PageBundle\Model\BlockInterface", "groups"={"sonata_api_read"}},
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
     * @return BlockInterface
     */
    public function getBlockBySlugAction($slug)
    {
        $block = $this->blockManager->findOneBy(['alias' => $slug]);

        if(!$block) {
            $block = $this->blockManager->findOneBy(['name' => $slug]);
        }

        if (null === $block) {
            throw new NotFoundHttpException(sprintf('Block with slug (%s) not found', $slug));
        }

        return $block;
    }

    /**
     * Updates a block.
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="block identifier"},
     *  },
     *  input={"class"="sonata_page_api_form_block", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Sonata\PageBundle\Model\Block", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while block creation",
     *      404="Returned when unable to find page"
     *  }
     * )
     *
     * @View(serializerGroups={"sonata_api_read"}, serializerEnableMaxDepthChecks=true)
     *
     * @param int     $id      A Block identifier
     * @param Request $request A Symfony request
     *
     * @throws NotFoundHttpException
     *
     * @return BlockInterface
     */
    public function putBlockAction($id, Request $request)
    {
        $block = $id ? $this->getBlock($id) : null;

        $method = $block ? 'PUT' : 'POST';

        $form = $this->formFactory->createNamed(null, 'sonata_page_api_form_block', $block, [
            'csrf_protection' => false,
            'method' => $method,
            'allow_extra_fields' => true
        ]);

        if($block->getId()) {
            $formDataKeys = array_keys($request->request->all());
            foreach ($form->all() as $child) {
                if(!in_array($child->getName(), $formDataKeys)) {
                    $form->remove($child->getName());
                }
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            $block = $form->getData();

            $this->blockManager->save($block);

            return $block;
        }

        return $form;
    }

}
