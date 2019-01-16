<?php

namespace Application\Sonata\MainBundle\Block;

use Application\Sonata\MainBundle\Model\Apply;
use Application\Sonata\MainBundle\Model\Email;
use Sonata\FormatterBundle\Block\FormatterBlockService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\Service\AbstractAdminBlockService;

/**
 * Class ApplyForm
 */
class ApplyForm extends FormatterBlockService
{
    use ContainerAwareTrait;


    public function configureSettings(OptionsResolver $resolver)
    {
        parent::configureSettings($resolver);

        $resolver->setDefault('template', '@ApplicationSonataMain/Block/applyFormView.html.twig');
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $emailModel = new Apply();
        $form = $this->container->get('form.factory')->create('Application\Sonata\MainBundle\Form\Type\ApplyMailType', $emailModel, [
            'action' => $this->container->get('router')->generate('_application_sonata_main_career'),
            'csrf_protection' => false
        ]);

        if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $mailTo = $this->container->hasParameter('mailer_apply_send_to') ? $this->container->getParameter('mailer_apply_send_to') : null;
                if (!$mailTo) {
                    $mailTo = 'pawel.kazmierczak@jaaqob.pl';
                }

                $message = (new \Swift_Message())
                    ->setReplyTo($emailModel->getEmail(), $emailModel->getFullName())
                    ->setSubject($emailModel->getSubject())
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setTo($mailTo)
                    ->setBody(
                        $this->container->get('twig')->render(
                            '@ApplicationSonataMain/Email/apply.html.twig',
                            array('data' => $emailModel, 'form' => $form->createView())
                        ),
                        'text/html'
                    );

                /* @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
                $file = $emailModel->getFile();
                $attachment = new \Swift_Attachment(file_get_contents($file->getRealPath()), $file->getClientOriginalName(), $file->getClientMimeType());
                $message->attach($attachment);

                $this->container->get('mailer')->send($message);

                $this->container->get('session')->getFlashBag()->add('success', 'Wiadomość została pomyślnie wysłana!');

                return new JsonResponse(['success' => true]);
            } else {
                $template = '@ApplicationSonataMain/Block/applyFormViewInner.html.twig';
                $html = $this->container->get('templating')->render($template, [
                    'form' => $form->createView(),
                    'errors' => true
                ]);

                return new JsonResponse(['success' => false, 'html' => $html]);
            }
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'form' => $form->createView()
        ), $response);
    }

}