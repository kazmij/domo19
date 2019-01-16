<?php

namespace Application\Sonata\OfferBundle\Entity;

use Sonata\CoreBundle\Model\BaseEntityManager;

class OfferImportManager extends BaseEntityManager {

    public function findByFileName($filename)
    {

        return $this->getEntityManager()
            ->getRepository($this->getRepository()->getClassName())
            ->createQueryBuilder('o')
            ->join('o.importFile', 'f')
            ->where('f.name = :filename')
            ->setParameter('filename', $filename)
            ->getQuery()
            ->getResult();
    }
}