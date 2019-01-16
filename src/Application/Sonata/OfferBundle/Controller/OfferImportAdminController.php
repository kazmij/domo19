<?php

namespace Application\Sonata\OfferBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OfferImportAdminController extends CRUDController
{

    public function checkImportAction($id) {

        $object = $this->admin->getSubject();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('Unable to find the object with id: %s', $id));
        }

        $completed = $object->getStatus() == 'completed';
        $error = $object->getStatus() == 'error';
        $translator = $this->container->get('translator.default');

        if($completed) {
            $response = [
                'completed' => $completed,
                'text' => $translator->trans('Completed'),
                'msg' => $translator->trans('Import #%import% has been successfully done!', ['%import%' => $object->getId()]),
                'offers' => $object->getOffers() ? $object->getOffers()->count() : 0
            ];
        } elseif($error) {
            $response = [
                'completed' => false,
                'error' => true,
                'text' => $translator->trans('Error'),
                'msg' => $translator->trans('An error occured during #%import% processing!', ['%import%' => $object->getId()])
            ];
        } else {
            $response = [
                'completed' => false
            ];
        }

        return new JsonResponse($response);
    }
}
