<?php

namespace Application\Sonata\MainBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;

class DoctrineSubscriber implements EventSubscriber
{

    /**
     * @var Container
     */
    private $container;

    private $cacheDriver;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        $this->cacheDriver = $container->get('doctrine.orm.default_result_cache');
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preFlush',
            'onFlush',
        );
    }

    /**
     * Change entity before store in the database
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        $requestStack = $this->container->get('request_stack');
        $currentRequest = $requestStack->getCurrentRequest();
    }

    /**
     * Change entity before store in the database
     *
     * @param PreFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        //$canRefreshCache = in_array($request->getMethod(), ['POST', 'PUT', 'PATCH']) && preg_match('/\/api\//im', $request->getRequestUri()) && !preg_match('/\/api\/login/im', $request->getRequestUri());

        //if ($canRefreshCache) {
            $restApiNamespace = 'rest_api_';
            if (count($uow->getScheduledEntityInsertions()) || count($uow->getScheduledEntityUpdates()) || count($uow->getScheduledEntityDeletions())) {
                $this->cacheDriver->setNamespace($restApiNamespace);
                $this->cacheDriver->flushAll();
            }
        //}
    }
}