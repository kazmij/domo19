<?php

namespace Application\Sonata\OfferBundle\Controller;

use Application\Sonata\MainBundle\Model\BuyModel;
use Application\Sonata\MainBundle\Model\Email;
use Application\Sonata\OfferBundle\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class OfferController extends Controller
{

    public function listAction(Request $request, $slug)
    {
        $category = $this->get('sonata.classification.manager.category')->findOneBy(['slug' => $slug]);

        $formOptions = [
            'csrf_protection' => false,
            'method' => 'GET'
        ];
        $buyModel = new BuyModel();
        $form = $this->container->get('form.factory')->createNamed(null, 'sonata_main_block_buy_form', $buyModel, $formOptions);
        $form->handleRequest($request);

        if($category) {
            $buyModel->setCategory($category);
        }

        $offersQb = $this->container->get('application_sonata_offer.admin.offer_helper')->getOffersQb($buyModel);

        /* @var $results \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination */
        $results = $this->container->get('knp_paginator')->paginate($offersQb, $request->get('p', 1), $buyModel->getPerPage(12), [
            'wrap-queries' => true
        ]);

        return $this->render('ApplicationSonataOfferBundle:Offer:list.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'results' => $results
        ]);
    }

    /**
     * @param Request $request
     * @param Offer $offer
     * @Entity("offer", expr="repository.findOneBySlug(slug)")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function viewAction(Request $request, Offer $offer)
    {
        $emailModel = new Email();
        $emailModel->setSendTo($offer->getContactEmail() ? $offer->getContactEmail() : null);
        $emailModel->setSubject('Oferta nr. ' . $offer->getOfferNumber() . ', ' . $offer->getOfferName());

        $form = $this->container->get('form.factory')->create('sonata_main_offer_form_mail', $emailModel);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $mailTo = $emailModel->getSendTo() ? $emailModel->getSendTo() : ($this->container->hasParameter('mailer_send_to') && $this->container->getParameter('mailer_send_to') ? $this->container->getParameter('mailer_send_to') : $emailModel->getEmail());

                $message = (new \Swift_Message())
                    ->setReplyTo($emailModel->getEmail(), $emailModel->getFullName())
                    ->setSubject($emailModel->getSubject())
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setTo($mailTo)
                    ->setBody(
                        $this->container->get('twig')->render(
                            '@ApplicationSonataOffer/Email/contact.html.twig',
                            array('data' => $emailModel, 'form' => $form->createView(), 'url' => $this->generateUrl($offer->getRouteName(), $offer->getRouteParams(), UrlGeneratorInterface::ABSOLUTE_URL))
                        ),
                        'text/html'
                    );

                $this->container->get('mailer')->send($message);

                $this->container->get('session')->getFlashBag()->add('success', 'Wiadomość została pomyślnie wysłana!');

                $this->redirectToRoute($offer->getRouteName(), $offer->getRouteParams());

                $this->container->get('session')->getFlashBag()->add('success', 'Wiadomość została pomyślnie wysłana!');
            }
        }

        $similarOffers = $this->container->get('application_sonata_offer.admin.offer_helper')->getSimmilarTo($offer);

        $response = $this->render('ApplicationSonataOfferBundle:Offer:view.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'similarOffers' => $similarOffers
        ]);

        return $response;
    }

    public function precinctAction(Request $request)
    {
        $result = [];

        $city = trim($request->get('city'));
        if ($city) {
            $result = $this->container->get('application_sonata_offer.admin.offer_helper')->getPrecinctForCity($city);
        }

        return new JsonResponse(['data' => $result]);
    }

    public function topSearchAction(Request $request)
    {
        $formOptions = [
            'csrf_protection' => false,
            'method' => 'GET',
            'action' => $this->container->get('router')->generate('application_sonata_offer_list')
        ];
        $buyModel = new BuyModel();
        $form = $this->container->get('form.factory')->createNamed(null, 'sonata_main_block_buy_mini_form', $buyModel, $formOptions);


        return $this->render('ApplicationSonataOfferBundle:Offer:topSearchForm.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
