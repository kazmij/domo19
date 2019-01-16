<?php

namespace Application\Sonata\MainBundle\Controller;

use Application\Sonata\MainBundle\Model\BuyModel;
use Application\Sonata\MainBundle\Model\Email;
use Application\Sonata\OfferBundle\Entity\Offer;
use Sonata\BlockBundle\Block\BlockContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{

    public function careerAction(Request $request)
    {
        $context = $this->container->get('sonata.block.context_manager')->get([
            'type' => 'sonata.main.block.apply',
            'settings' => [
                'content' => null
            ]
        ]);

        return $this->container->get('sonata.main.block.apply')->execute($context);
    }

    public function flashAction(Request $request)
    {
        $html = $this->renderView('ApplicationSonataMainBundle:Default:flash.html.twig');

        $response = new JsonResponse([
            'html' => $html
        ]);

        $response->setPrivate();

        return $response;
    }

    public function contactAction(Request $request)
    {
        $emailModel = new Email();
        $form = $this->container->get('form.factory')->create('Application\Sonata\MainBundle\Form\Type\ApiMailType', $emailModel, [
            'action' => $request->getUri()
        ]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $mailTo = $this->container->hasParameter('mailer_send_to') && $this->container->getParameter('mailer_send_to') ? $this->container->getParameter('mailer_send_to') : $emailModel->getEmail();

                $message = (new \Swift_Message())
                    ->setReplyTo($emailModel->getEmail(), $emailModel->getFullName())
                    ->setSubject($emailModel->getSubject())
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setTo($mailTo)
                    ->setBody(
                        $this->container->get('twig')->render(
                            '@ApplicationSonataMain/Email/contact.html.twig',
                            array('data' => $emailModel, 'form' => $form->createView())
                        ),
                        'text/html'
                    );

                $this->container->get('mailer')->send($message);

                $this->container->get('session')->getFlashBag()->add('success', 'Wiadomość została pomyślnie wysłana!');

                $emailModel = new Email();
                $form = $this->container->get('form.factory')->create('Application\Sonata\MainBundle\Form\Type\ApiMailType', $emailModel, [
                    'action' => $request->getUri()
                ]);
            }
        }

        $response = $this->render('ApplicationSonataMainBundle:Default:contact.html.twig', [
            'form' => $form->createView()
        ]);

        $response->setPrivate();

        return $response;
    }
}
