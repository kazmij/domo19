<?php

namespace Application\Sonata\OfferBundle\Service;


use Application\Sonata\ClassificationBundle\Entity\Category;
use Application\Sonata\MainBundle\Model\BuyModel;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Application\Sonata\MediaBundle\Entity\GalleryHasMedia;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\OfferBundle\Entity\Offer;
use Application\Sonata\OfferBundle\Entity\OfferAttribute;
use Application\Sonata\OfferBundle\Entity\OfferAttributeManager;
use Application\Sonata\OfferBundle\Entity\OfferImport;
use Application\Sonata\OfferBundle\Entity\OfferManager;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Internal\Hydration\HydrationException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Sluggable\Util\Urlizer;
use Sonata\ClassificationBundle\Entity\CategoryManager;
use Sonata\ClassificationBundle\Entity\ContextManager;
use Sonata\MediaBundle\Entity\MediaManager;
use Sonata\MediaBundle\Provider\FileProvider;
use Sonata\UserBundle\Entity\UserManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Exception\InvalidArgumentException;

/**
 * Class Helper
 * @package Application\Sonata\OfferBundle\Service
 */
class Helper
{
    const OFFER_CITY_ATTRIBUTE_KEY = 'locationCityName';
    const OFFER_LOCATION_ATTRIBUTE_KEY = 'locationPrecinctName';

    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var ContextManager
     */
    private $contextManager;

    /**
     * @var OfferManager
     */
    private $offerManager;

    /**
     * @var OfferAttributeManager
     */
    private $offerAttributeManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var EntityManager
     */
    private $em;

    use ContainerAwareTrait;

    /**
     * Custom service constructor
     */
    public function _construct()
    {
        $this->categoryManager = $this->container->get('sonata.classification.manager.category');
        $this->contextManager = $this->container->get('sonata.classification.manager.context');
        $this->offerManager = $this->container->get('application_sonata_offer.manager.offer');
        $this->mediaManager = $this->container->get('sonata.media.manager.media');
        $this->offerAttributeManager = $this->container->get('application_sonata_offer.manager.offer_attribute');
        $this->userManager = $this->container->get('sonata.user.manager.user');
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
    }

    public function getCities()
    {
        $cityCategory = $this->categoryManager->findOneBy(['name' => self::OFFER_CITY_ATTRIBUTE_KEY]);

        if ($cityCategory) {
            $cities = $this->em->getRepository(OfferAttribute::class)->createQueryBuilder('o')
                ->select('o.value')
                ->where('o.attribute = :attribute')
                ->groupBy('o.value')
                ->orderby('o.value')
                ->setParameter('attribute', $cityCategory->getId())
                ->getQuery()
                ->useQueryCache(true)
                ->setCacheRegion('one_hour')
                ->getResult(AbstractQuery::HYDRATE_ARRAY);

            $tmp = [];

            foreach ($cities as $city) {
                $tmp[$city['value']] = $city['value'];
            }

            $cities = $tmp;

            return $cities;
        } else {

            return [];
        }
    }

    public function getPrecincts()
    {
        $city = $this->container->get('request_stack')->getCurrentRequest()->get('city');
        if ($city) {

            return $this->getPrecinctForCity($city);
        } else {

            return [];
        }
    }

    public function getOffersQb(BuyModel $model)
    {
        $categoryManager = $this->container->get('sonata.media.manager.category');
        $qb = $this->em->getRepository(Offer::class)->createQueryBuilder('o');

        if($model->getCategory()) {
            $qb
                ->join('o.categories', 'c')
                ->andWhere($qb->expr()->eq('c.id', $model->getCategory()->getId()));
        }


        $qb->groupBy('o.id');

        $query = $qb
            ->getQuery()
            ->useResultCache(false)
            ->setCacheRegion('one_hour');

        return $query;

    }

    public function getSimmilarTo(Offer $offer, $limit = 3)
    {
        $categoryManager = $this->container->get('sonata.media.manager.category');
        $qb = $this->em->getRepository(Offer::class)->createQueryBuilder('o');
        $qb->join('o.attributes', 'a');
        $qb_ = $this->em->createQueryBuilder();

        $conditions = [];

        if ($offer->getCity()) {
            $cityCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_CITY_KEY]);
            if ($cityCategory) {
                $conditions[] = $qb->expr()->andX(
                    $qb->expr()->eq('a.attribute', $cityCategory->getId()),
                    $qb->expr()->like($qb->expr()->upper('a.value'), $qb->expr()->literal('%' . strtoupper($offer->getCity()) . '%'))
                );
            } else {
                throw new NotFoundHttpException('City category is not set!');
            }
        }

//        if ($offer->getPrecinct()) {
//            $precinctCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_PRECINCT_KEY]);
//            if ($precinctCategory) {
//                $conditions[] =$qb_->expr()->andX(
//                    $qb_->expr()->eq('a.attribute', $precinctCategory->getId()),
//                    $qb_->expr()->like($qb_->expr()->upper('a.value'), $qb_->expr()->literal('%' . strtoupper($offer->getPrecinct()) . '%'))
//                );
//            } else {
//                throw new NotFoundHttpException('Precinct category is not set!');
//            }
//        }
//
//        if ($offer->getAreaTotal()) {
//            $areaTotalCategory = $categoryManager->findOneBy(['name' => OfferAttribute::AREA_SIZE_TOTAL_KEY]);
//            if ($areaTotalCategory) {
//                $from = $offer->getAreaTotal() - 25;
//                $to = $offer->getAreaTotal() + 25;
//
//                $conditions[] = $qb_->expr()->andX(
//                    $qb_->expr()->eq('a.attribute', $areaTotalCategory->getId()),
//                    $qb_->expr()->between('a.valueNumeric', $from, $to)
//                );
//            } else {
//                throw new NotFoundHttpException('Area category is not set!');
//            }
//        }

        $qb->andWhere((implode(' OR ', $conditions)));
        $qb->andWhere('o.id != ' . $offer->getId());
        $qb
            ->setMaxResults($limit)
            ->addOrderBy('o.createdAt', 'DESC');

        return $qb->getQuery()
            ->useQueryCache(true)
            ->setCacheRegion('one_hour')
            ->getResult();
    }

    public function getPrecinctForCity($city)
    {
        $categoryManager = $this->container->get('sonata.media.manager.category');
        $precinctCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_PRECINCT_KEY]);
        $cityCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_CITY_KEY]);
        if ($cityCategory && $precinctCategory) {
            $qb = $this->em->getRepository(Offer::class)->createQueryBuilder('o');
            $result = $qb
                ->select('p.value')
                ->join('o.attributes', 'p')
                ->join('o.attributes', 'c')
                ->andWhere($qb->expr()->eq('p.attribute', $precinctCategory->getId()))
                ->andWhere($qb->expr()->eq('c.attribute', $cityCategory->getId()))
                ->andWhere($qb->expr()->eq($qb->expr()->upper('c.value'), $qb->expr()->upper($qb->expr()->literal($city))))
                ->groupBy('p.value')
                ->orderBy('p.value', 'ASC')
                ->getQuery()
                ->useQueryCache(true)
                ->setCacheRegion('one_hour')
                ->getArrayResult();
            $tmp = [];
            foreach ($result as $row) {
                $tmp[$row['value']] = $row['value'];
            }
            $result = $tmp;

            return $result;
        }

        return [];
    }
}