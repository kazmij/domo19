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

        if ($model->getTransactionType() && strtoupper($model->getTransactionType()->getName()) != 'DOWOLNY') {
            $qb->leftJoin('o.attributes', 'type');
            $qb->andWhere($qb->expr()->eq('type.attributeValue', $model->getTransactionType()->getId()));
        }

        if ($model->getBuildingType() && strtoupper($model->getBuildingType()->getName()) != 'DOWOLNY') {
            $qb->leftJoin('o.attributes', 'buildingType');
            $qb->andWhere($qb->expr()->eq('buildingType.attributeValue', $model->getBuildingType()->getId()));
        }

        if ($model->getMarketType()) {
            $qb->leftJoin('o.attributes', 'market');
            $marketConditions = [];
            foreach ($model->getMarketType() as $marketType) {
                $marketConditions[] = $qb->expr()->eq('market.attributeValue', $marketType->getId());
            }
            if ($marketConditions) {
                $qb->andWhere(implode(' OR ', $marketConditions));
            }
        }

        if ($model->getCity()) {
            $cityCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_CITY_KEY]);
            if ($cityCategory) {
                $qb->leftJoin('o.attributes', 'city');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('city.attribute', $cityCategory->getId()),
                    $qb->expr()->like($qb->expr()->upper('city.value'), $qb->expr()->literal('%' . strtoupper($model->getCity()) . '%'))
                ));
            } else {
                throw new NotFoundHttpException('City category is not set!');
            }
        }

        if ($model->getPrecinct()) {
            $precinctCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_PRECINCT_KEY]);
            if ($precinctCategory) {

                $qb->leftJoin('o.attributes', 'precinct');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('precinct.attribute', $precinctCategory->getId()),
                    $qb->expr()->like($qb->expr()->upper('precinct.value'), $qb->expr()->literal('%' . strtoupper($model->getPrecinct()) . '%'))
                ));
            } else {
                throw new NotFoundHttpException('Precinct category is not set!');
            }
        }

        if ($model->getStreet()) {
            $street = trim($model->getStreet());
            $cityCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_CITY_KEY]);
            $streetCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_STREET_KEY]);
            $precinctCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_PRECINCT_KEY]);
            $provinceCategory = $categoryManager->findOneBy(['name' => OfferAttribute::LOCATION_PROVINCE_KEY]);
            if ($cityCategory && $streetCategory && $precinctCategory && $provinceCategory) {
                $qb->leftJoin('o.attributes', 'searchBy');
                $qb->andWhere($qb->expr()->in('searchBy.attribute', [$cityCategory->getId(), $streetCategory->getId(), $precinctCategory->getId(), $provinceCategory->getId()]));
                $queryString = '';
                if ($street) {
                    $streets = explode(' ', $street);
                    foreach ($streets as $st) {
                        $st = trim($st);
                        if ($st) {
                            $phrases[] = "*" . ($st) . "*";
                        }
                    }
                }
                foreach ($phrases as $key => $phrase) {
                    if ($key == 0) {
                        $queryString = " MATCH_AGAINST (o.searchPhrase, '" . $phrase . "') > 0 ";
                    } else {
                        $queryString .= " AND MATCH_AGAINST (o.searchPhrase, '" . $phrase . "') > 0 ";
                    }
                }
                $qb->addSelect("(MATCH_AGAINST (o.searchPhrase, '" . (implode(' ', $phrases)) . "')) as fulltextSearch");
                $qb->andWhere($queryString);
                $orderByString = 'fulltextSearch';
            } else {
                throw new NotFoundHttpException('Street category is not set!');
            }
        }

        if ($model->getAreaFrom() || $model->getAreaTo()) {
            $areaTotalCategory = $categoryManager->findOneBy(['name' => OfferAttribute::AREA_SIZE_TOTAL_KEY]);
            if ($areaTotalCategory) {
                $qb->leftJoin('o.attributes', 'area');
                $from = $model->getAreaFrom() ? $model->getAreaFrom() : 0;
                $to = $model->getAreaTo() ? $model->getAreaTo() : 100000000;

                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('area.attribute', $areaTotalCategory->getId()),
                    $qb->expr()->between('area.valueNumeric', $from, $to)
                ));
            } else {
                throw new NotFoundHttpException('Area category is not set!');
            }
        }

        if ($model->getPriceFrom() || $model->getPriceTo()) {
            $priceCategory = $categoryManager->findOneBy(['name' => OfferAttribute::PRICE_KEY]);
            if ($priceCategory) {
                $qb->leftJoin('o.attributes', 'price');
                $from = $model->getPriceFrom() ? $model->getPriceFrom() : 0;
                $to = $model->getPriceTo() ? $model->getPriceTo() : 100000000000000;

                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('price.attribute', $priceCategory->getId()),
                    $qb->expr()->between('price.valueNumeric', $from, $to)
                ));
            } else {
                throw new NotFoundHttpException('Price category is not set!');
            }
        }


        if ($model->getRoomFrom() || $model->getRoomTo()) {
            $roomCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ROOMS_NUMBER_KEY]);
            if ($roomCategory) {
                $qb->leftJoin('o.attributes', 'room');
                $from = $model->getRoomFrom() ? $model->getRoomFrom() : 0;
                $to = $model->getRoomTo() ? $model->getRoomTo() : 100000;

                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('room.attribute', $roomCategory->getId()),
                    $qb->expr()->between('room.valueNumeric', $from, $to)
                ));
            } else {
                throw new NotFoundHttpException('Room category is not set!');
            }
        }

        if (is_numeric($model->getFloorFrom()) || is_numeric($model->getFloorTo())) {
            $floorCategory = $categoryManager->findOneBy(['name' => OfferAttribute::FLOOR_KEY]);
            if ($floorCategory) {
                $qb->leftJoin('o.attributes', 'floor');
                $from = is_numeric($model->getFloorFrom()) ? $model->getFloorFrom() : 0;
                $to = is_numeric($model->getFloorTo()) ? $model->getFloorTo() : 100;

                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('floor.attribute', $floorCategory->getId()),
                    $qb->expr()->between('floor.valueNumeric', $from, $to)
                ));
            } else {
                throw new NotFoundHttpException('Floor category is not set!');
            }
        }

        if ($model->getYearFrom() || $model->getYearTo()) {
            $yearCategory = $categoryManager->findOneBy(['name' => OfferAttribute::BUILDING_YEAR_KEY]);
            if ($yearCategory) {
                $qb->leftJoin('o.attributes', 'y');
                $from = $model->getYearFrom() ? $model->getYearFrom() : 0;
                $to = $model->getYearTo() ? $model->getYearTo() : 3000;

                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('y.attribute', $yearCategory->getId()),
                    $qb->expr()->between('y.valueNumeric', $from, $to)
                ));
            } else {
                throw new NotFoundHttpException('Year category is not set!');
            }
        }

        if ($model->getAdditionalGarage()) {
            $garageCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_GARAGE_KEY]);
            if ($garageCategory) {
                $qb->leftJoin('o.attributes', 'garage');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('garage.attribute', $garageCategory->getId()),
                    $qb->expr()->isNotNull('garage.valueNumeric'),
                    $qb->expr()->eq('garage.valueNumeric', 1)
                ));
            } else {
                throw new NotFoundHttpException('Garage category is not set!');
            }
        }

        if ($model->getAdditionalBalcony()) {
            $balconyCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_BALCONY_KEY]);
            if ($balconyCategory) {
                $qb->leftJoin('o.attributes', 'balcony');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('balcony.attribute', $balconyCategory->getId()),
                    $qb->expr()->isNotNull('balcony.valueNumeric'),
                    $qb->expr()->eq('balcony.valueNumeric', '1')
                ));
            } else {
                throw new NotFoundHttpException('Balcony category is not set!');
            }
        }

        if ($model->getAdditionalBasement()) {
            $basementCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_BASEMENT_KEY]);
            if ($basementCategory) {
                $qb->leftJoin('o.attributes', 'basement');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('basement.attribute', $basementCategory->getId()),
                    $qb->expr()->isNotNull('basement.valueNumeric'),
                    $qb->expr()->eq('basement.valueNumeric', '1')
                ));
            } else {
                throw new NotFoundHttpException('Basement category is not set!');
            }
        }

        if ($model->getBuildingElevatornumber()) {
            $elevatorCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_ELEVATOR_KEY]);
            if ($elevatorCategory) {
                $qb->leftJoin('o.attributes', 'elevator');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('elevator.attribute', $elevatorCategory->getId()),
                    $qb->expr()->isNotNull('elevator.valueNumeric'),
                    $qb->expr()->gt('elevator.valueNumeric', 0)
                ));
            } else {
                throw new NotFoundHttpException('Elevator category is not set!');
            }
        }

        if ($model->getAdditionalGarden()) {
            $gardenCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_GARDEN_KEY]);
            if ($gardenCategory) {
                $qb->leftJoin('o.attributes', 'garden');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('garden.attribute', $gardenCategory->getId()),
                    $qb->expr()->isNotNull('garden.valueNumeric'),
                    $qb->expr()->eq('garden.valueNumeric', '1')
                ));
            } else {
                throw new NotFoundHttpException('Garden category is not set!');
            }
        }

        if ($model->getBuildingAdapted()) {
            $adaptedCategory = $categoryManager->findOneBy(['name' => OfferAttribute::ADDITIONAL_ADAPTED_KEY]);
            if ($adaptedCategory) {
                $qb->leftJoin('o.attributes', 'adapted');
                $qb->andWhere($qb->expr()->andX(
                    $qb->expr()->eq('adapted.attribute', $adaptedCategory->getId()),
                    $qb->expr()->isNotNull('adapted.valueNumeric'),
                    $qb->expr()->eq('adapted.valueNumeric', '1')
                ));
            } else {
                throw new NotFoundHttpException('Adapted category is not set!');
            }
        }

        if (isset($orderByString)) {
            $qb->addOrderBy($orderByString, 'DESC');
        }

        switch ($model->getSortType()) {
            case 'Latest':
                $qb->addOrderBy('o.createdAt', 'DESC');
                break;

            case 'Oldest':
                $qb->addOrderBy('o.createdAt', 'ASC');
                break;

            case 'Price_Desc':
                $priceCategory = $categoryManager->findOneBy(['name' => OfferAttribute::PRICE_KEY]);
                if ($priceCategory) {
                    $qb->leftJoin('o.attributes', 'priceSort');
                    $qb->andWhere($qb->expr()->eq('priceSort.attribute', $priceCategory->getId()));
                    $qb->orderBy('priceSoSELECT * FROM `offer` WHERE 1rt.valueNumeric', 'DESC');
                } else {
                    throw new NotFoundHttpException('Price category is not set!');
                }
                break;

            case 'Price_Asc':
                $priceCategory = $categoryManager->findOneBy(['name' => OfferAttribute::PRICE_KEY]);
                if ($priceCategory) {
                    $qb->leftJoin('o.attributes', 'priceSort');
                    $qb->andWhere($qb->expr()->eq('priceSort.attribute', $priceCategory->getId()));
                    $qb->orderBy('priceSort.valueNumeric', 'ASC');
                } else {
                    throw new NotFoundHttpException('Price category is not set!');
                }
                break;

            case 'Area_Desc':
                $areaCategory = $categoryManager->findOneBy(['name' => OfferAttribute::AREA_SIZE_TOTAL_KEY]);
                if ($areaCategory) {
                    $qb->leftJoin('o.attributes', 'area');
                    $qb->andWhere($qb->expr()->eq('area.attribute', $areaCategory->getId()));
                    $qb->addOrderBy('area.valueNumeric', 'DESC');
                } else {
                    throw new NotFoundHttpException('Area category is not set!');
                }
                break;

            case 'Area_Asc':
                $areaCategory = $categoryManager->findOneBy(['name' => OfferAttribute::AREA_SIZE_TOTAL_KEY]);
                if ($areaCategory) {
                    $qb->leftJoin('o.attributes', 'area');
                    $qb->andWhere($qb->expr()->eq('area.attribute', $areaCategory->getId()));
                    $qb->addOrderBy('area.valueNumeric', 'ASC');
                } else {
                    throw new NotFoundHttpException('Area category is not set!');
                }
                break;
        }

        $cache = $this->em->getConfiguration()->getResultCacheImpl();

        $qb->groupBy('o.id');

        $query = $qb
            ->getQuery()
            ->useResultCache(true)
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