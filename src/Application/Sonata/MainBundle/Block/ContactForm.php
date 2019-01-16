<?php

namespace Application\Sonata\MainBundle\Block;

use Application\Sonata\MainBundle\Model\Email;
use Sonata\FormatterBundle\Block\FormatterBlockService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;

/**
 * Class ContactForm
 */
class ContactForm extends FormatterBlockService
{
    use ContainerAwareTrait;


    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);

        $resolver->setDefault('template', '@ApplicationSonataMain/Block/contactForm.html.twig');
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        //$request = $this->container->get('request_stack')->getCurrentRequest();

//        $emailModel = new Email();
//        $form = $this->container->get('form.factory')->create('Application\Sonata\MainBundle\Form\Type\ApiMailType', $emailModel);
//
//        if ($request->isMethod('POST')) {
//            $form->handleRequest($request);
//
//            if ($form->isValid()) {
//
//                $mailTo = $this->container->hasParameter('mailer_send_to') && $this->container->getParameter('mailer_send_to') ? $this->container->getParameter('mailer_send_to') : $emailModel->getEmail();
//
//                $message = (new \Swift_Message())
//                    ->setReplyTo($emailModel->getEmail(), $emailModel->getFullName())
//                    ->setSubject($emailModel->getSubject())
//                    ->setFrom($this->container->getParameter('mailer_user'))
//                    ->setTo($mailTo)
//                    ->setBody(
//                        $this->container->get('twig')->render(
//                            '@ApplicationSonataMain/Email/contact.html.twig',
//                            array('data' => $emailModel, 'form' => $form->createView())
//                        ),
//                        'text/html'
//                    );
//
//                $this->container->get('mailer')->send($message);
//
//                $this->container->get('session')->getFlashBag()->add('success', 'Wiadomość została pomyślnie wysłana!');
//
//                return new RedirectResponse($request->server->has('HTTP_REFERER') ? $request->server->get('HTTP_REFERER') : '/');
//            }
//        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            //'form' => $form->createView()
        ), $response);
    }

}