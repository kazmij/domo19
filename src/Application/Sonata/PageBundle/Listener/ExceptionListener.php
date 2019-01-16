<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Listener;

use Psr\Log\LoggerInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\CmsManager\DecoratorStrategyInterface;
use Sonata\PageBundle\Exception\InternalErrorException;
use Sonata\PageBundle\Page\PageServiceManagerInterface;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;
use Sonata\PageBundle\Listener\ExceptionListener as BaseExceptionListener;

/**
 * ExceptionListener.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class ExceptionListener extends BaseExceptionListener
{

    /**
     * Handles a kernel exception.
     *
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof InternalErrorException) {
            $this->handleInternalError($event);
        } else {
            $this->handleNativeError($event);
        }
    }

    /**
     * Handles an internal error.
     *
     * @param GetResponseForExceptionEvent $event
     */
    private function handleInternalError(GetResponseForExceptionEvent $event)
    {
        if (false === $this->debug) {
            $this->logger->error($event->getException()->getMessage(), [
                'exception' => $event->getException(),
            ]);

            return;
        }

        $content = $this->templating->render('@SonataPage/internal_error.html.twig', [
            'exception' => $event->getException(),
        ]);

        $event->setResponse(new Response($content, 500));
    }


    /**
     * Handles a native error.
     *
     * @param GetResponseForExceptionEvent $event
     *
     * @throws mixed
     */
    private function handleNativeError(GetResponseForExceptionEvent $event)
    {
        if (true === $this->debug) {
            return;
        }

        if (true === $this->status) {
            return;
        }

        $this->status = true;

        $exception = $event->getException();
        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;

        $cmsManager = $this->cmsManagerSelector->retrieve();

        if ($event->getRequest()->get('_route') && !$this->decoratorStrategy->isRouteNameDecorable($event->getRequest()->get('_route'))) {
            return;
        }

        if (!$this->decoratorStrategy->isRouteUriDecorable($event->getRequest()->getPathInfo())) {
            return;
        }

        if (!$this->hasErrorCode($statusCode)) {
            return;
        }

        $message = sprintf('%s: %s (uncaught exception) at %s line %s', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine());

        $this->logException($exception, $exception, $message);

        try {
            $page = $this->getErrorCodePage($statusCode);

            $cmsManager->setCurrentPage($page);

            if ($page->getSite()->getLocale() !== $event->getRequest()->getLocale()) {
                // Compare locales because Request returns the default one if null.

                // If 404, LocaleListener from HttpKernel component of Symfony is not called.
                // It uses the "_locale" attribute set by SiteSelectorInterface to set the request locale.
                // So in order to translate messages, force here the locale with the site.
                $event->getRequest()->setLocale($page->getSite()->getLocale());
            }

            $response = $this->pageServiceManager->execute($page, $event->getRequest(), [], new Response('', $statusCode));
        } catch (\Exception $e) {
            $this->logException($exception, $e);

            $event->setException($e);
            $this->handleInternalError($event);

            return;
        }

        $event->setResponse($response);
    }

    /**
     * Logs exceptions.
     *
     * @param \Exception  $originalException  Original exception that called the listener
     * @param \Exception  $generatedException Generated exception
     * @param string|null $message            Message to log
     */
    private function logException(\Exception $originalException, \Exception $generatedException, $message = null)
    {
        if (!$message) {
            $message = sprintf('Exception thrown when handling an exception (%s: %s)', get_class($generatedException), $generatedException->getMessage());
        }

        if (null !== $this->logger) {
            if (!$originalException instanceof HttpExceptionInterface || $originalException->getStatusCode() >= 500) {
                $this->logger->critical($message, ['exception' => $originalException]);
            } else {
                $this->logger->error($message, ['exception' => $originalException]);
            }
        } else {
            error_log($message);
        }
    }
}
