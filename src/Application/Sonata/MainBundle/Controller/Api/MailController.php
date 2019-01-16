<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MainBundle\Controller\Api;

use Application\Sonata\MainBundle\Model\Email;
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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MailController
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * MailController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Post email.
     *
     * @ApiDoc(
     *  input={"class"="sonata_main_api_form_mail", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Application\Sonata\MainBundle\Model\Email", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while post creation",
     *  }
     * )
     *
     * @REST\Route(requirements={"_format"="json|xml"})
     *
     * @param Request $request A Symfony request
     *
     * @throws NotFoundHttpException
     *
     * @return \Application\Sonata\MainBundle\Model\Email
     */
    public function postMailerAction(Request $request)
    {
        return $this->handleSendMail($request);
    }

    /**
     *
     * @param Request $request Symfony request
     * @param int|null $id A post identifier
     *
     * @return FormInterface
     */
    protected function handleSendMail($request)
    {
        $mail = new Email();

        $method = 'POST';

        $formOptions = [
            'csrf_protection' => false,
            'method' => $method,
            'allow_extra_fields' => true
        ];

        $form = $this->container->get('form.factory')->createNamed(null, 'sonata_main_api_form_mail', $mail, $formOptions);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $mailTo = $this->container->hasParameter('mailer_send_to') && $this->container->getParameter('mailer_send_to') ? $this->container->getParameter('mailer_send_to') : $mail->getEmail();
            $message = (new \Swift_Message())
                ->setReplyTo($mail->getEmail(), $mail->getFullName())
                ->setSubject($mail->getSubject())
                ->setFrom($this->container->getParameter('mailer_user'))
                ->setTo($mailTo)
                ->setBody(
                    $this->container->get('twig')->render(
                        '@ApplicationSonataMain/Email/contact.html.twig',
                        array('data' => $mail, 'form' => $form->createView())
                    ),
                    'text/html'
                );

            $this->container->get('mailer')->send($message);

            $context = new Context();
            $context->setGroups(['sonata_api_read']);

            // simplify when dropping FOSRest < 2.1
            if (method_exists($context, 'enableMaxDepth')) {
                $context->enableMaxDepth();
            } else {
                $context->setMaxDepth(10);
            }

            $mail->setStatus('Email został wysłany pomyślnie na adres: ' . $mailTo);

            $mail->setSubject(null);
            $mail->setEmail(null);
            $mail->setBody(null);
            $mail->setFullName(null);
            $view = View::create($mail);
            $view->setContext($context);

            return $view;
        }

        return $form;
    }


}
