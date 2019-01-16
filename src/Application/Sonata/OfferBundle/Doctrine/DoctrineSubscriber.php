<?php

namespace Application\Sonata\OfferBundle\Doctrine;

use Application\Sonata\OfferBundle\Entity\Offer;
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

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
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

    }

    /**
     * Change entity before store in the database
     *
     * @param PreFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entityUpdate) {
            if ($entityUpdate instanceof Offer) {
                $this->updateSearchPhrase($entityUpdate);
            }
        }

        foreach ($uow->getScheduledEntityInsertions() as $entityInsertion) {
            if ($entityInsertion instanceof Offer) {
                $this->updateSearchPhrase($entityInsertion);
            }
        }
    }

    private function updateSearchPhrase(Offer $offer)
    {
        $fields = [];
        $fields[] = $offer->getStreet();
        $fields[] = $offer->getCity();
        $fields[] = $offer->getPrecinct();
        $fields[] = $offer->getProvince();
        $fields = array_filter($fields, function ($entry) {
            return $entry && strlen($entry);
        });
        $offer->setSearchPhrase(implode(' ', $fields));
    }
}