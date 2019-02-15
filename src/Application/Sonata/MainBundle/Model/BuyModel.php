<?php

namespace Application\Sonata\MainBundle\Model;

use Application\Sonata\ClassificationBundle\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class BuyModel {

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @var string
     */
    protected $transactionType;

    /**
     * @var string
     */
    protected $place;

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @var Category
     */
    protected $category;

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * @var string
     */
    protected $streetToCity;

    /**
     * @return string
     */
    public function getStreetToCity()
    {
        return $this->streetToCity;
    }

    /**
     * @param string $streetToCity
     */
    public function setStreetToCity($streetToCity)
    {
        $this->streetToCity = $streetToCity;
    }

    /**
     * @var string
     */
    protected $marketType;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $province;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var integer
     */
    protected $areaFrom;

    /**
     * @var integer
     */
    protected $areaTo;

    /**
     * @var integer
     */
    protected $usableAreaFrom;

    /**
     * @return int
     */
    public function getUsableAreaFrom()
    {
        return $this->usableAreaFrom;
    }

    /**
     * @param int $usableAreaFrom
     */
    public function setUsableAreaFrom($usableAreaFrom)
    {
        $this->usableAreaFrom = $usableAreaFrom;
    }

    /**
     * @return int
     */
    public function getUsableAreaTo()
    {
        return $this->usableAreaTo;
    }

    /**
     * @param int $usableAreaTo
     */
    public function setUsableAreaTo($usableAreaTo)
    {
        $this->usableAreaTo = $usableAreaTo;
    }

    /**
     * @var integer
     */
    protected $usableAreaTo;

    /**
     * @return int
     */
    public function getRoomFrom()
    {
        return $this->roomFrom;
    }

    /**
     * @param int $roomFrom
     */
    public function setRoomFrom($roomFrom)
    {
        $this->roomFrom = $roomFrom;
    }

    /**
     * @return int
     */
    public function getRoomTo()
    {
        return $this->roomTo;
    }

    /**
     * @param int $roomTo
     */
    public function setRoomTo($roomTo)
    {
        $this->roomTo = $roomTo;
    }

    /**
     * @var integer
     */
    protected $roomFrom;

    /**
     * @return int
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * @param int $priceFrom
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;
    }

    /**
     * @return int
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * @param int $priceTo
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;
    }

    /**
     * @var integer
     */
    protected $roomTo;

    /**
     * @return int
     */
    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    /**
     * @param int $yearFrom
     */
    public function setYearFrom($yearFrom)
    {
        $this->yearFrom = $yearFrom;
    }

    /**
     * @return int
     */
    public function getYearTo()
    {
        return $this->yearTo;
    }

    /**
     * @param int $yearTo
     */
    public function setYearTo($yearTo)
    {
        $this->yearTo = $yearTo;
    }

    /**
     * @var integer
     */
    protected $priceFrom;

    /**
     * @var integer
     */
    protected $priceTo;

    /**
     * @var integer
     */
    protected $yearFrom;

    /**
     * @var integer
     */
    protected $yearTo;

    /**
     * @return int
     */
    public function getAngleOfInclination()
    {
        return $this->angleOfInclination;
    }

    /**
     * @param int $angleOfInclination
     */
    public function setAngleOfInclination($angleOfInclination)
    {
        $this->angleOfInclination = $angleOfInclination;
    }

    /**
     * @var integer
     */
    protected $angleOfInclination;

    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    }

    /**
     * @var integer
     */
    protected $floor;

    /**
     * @var integer
     */
    protected $floorFrom;

    /**
     * @var integer
     */
    protected $floorTo;

    /**
     * @return int
     */
    public function getFloorFrom()
    {
        return $this->floorFrom;
    }

    /**
     * @param int $floorFrom
     */
    public function setFloorFrom($floorFrom)
    {
        $this->floorFrom = $floorFrom;
    }

    /**
     * @return int
     */
    public function getFloorTo()
    {
        return $this->floorTo;
    }

    /**
     * @param int $floorTo
     */
    public function setFloorTo($floorTo)
    {
        $this->floorTo = $floorTo;
    }

    /**
     * @return int
     */
    public function getAreaFrom()
    {
        return $this->areaFrom;
    }

    /**
     * @param int $areaFrom
     */
    public function setAreaFrom($areaFrom)
    {
        $this->areaFrom = $areaFrom;
    }

    /**
     * @return int
     */
    public function getAreaTo()
    {
        return $this->areaTo;
    }

    /**
     * @param int $areaTo
     */
    public function setAreaTo($areaTo)
    {
        $this->areaTo = $areaTo;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getPrecinct()
    {
        return $this->precinct;
    }

    /**
     * @param string $precinct
     */
    public function setPrecinct($precinct)
    {
        $this->precinct = $precinct;
    }

    /**
     * @var string
     */
    protected $precinct;

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getMarketType()
    {
        return $this->marketType;
    }

    /**
     * @param string $marketType
     */
    public function setMarketType($marketType)
    {
        $this->marketType = $marketType;
    }

    /**
     * @return string
     */
    public function getBuildingType()
    {
        return $this->buildingType;
    }

    /**
     * @param string $buildingType
     */
    public function setBuildingType($buildingType)
    {
        $this->buildingType = $buildingType;
    }

    /**
     * @var string
     */
    protected $buildingType;

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param string $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @var string
     */
    protected $additionalGarage;

    /**
     * @var string
     */
    protected $additionalBalcony;

    /**
     * @return string
     */
    public function getAdditionalBalcony()
    {
        return $this->additionalBalcony;
    }

    /**
     * @param string $additionalBalcony
     */
    public function setAdditionalBalcony($additionalBalcony)
    {
        $this->additionalBalcony = $additionalBalcony;
    }

    /**
     * @return string
     */
    public function getBuildingElevatornumber()
    {
        return $this->buildingElevatornumber;
    }

    /**
     * @param string $buildingElevatornumber
     */
    public function setBuildingElevatornumber($buildingElevatornumber)
    {
        $this->buildingElevatornumber = $buildingElevatornumber;
    }

    /**
     * @return string
     */
    public function getAdditionalGarden()
    {
        return $this->additionalGarden;
    }

    /**
     * @param string $additionalGarden
     */
    public function setAdditionalGarden($additionalGarden)
    {
        $this->additionalGarden = $additionalGarden;
    }

    /**
     * @return string
     */
    public function getAdditionalBasement()
    {
        return $this->additionalBasement;
    }

    /**
     * @param string $additionalBasement
     */
    public function setAdditionalBasement($additionalBasement)
    {
        $this->additionalBasement = $additionalBasement;
    }

    /**
     * @return string
     */
    public function getBuildingAdapted()
    {
        return $this->buildingAdapted;
    }

    /**
     * @param string $buildingAdapted
     */
    public function setBuildingAdapted($buildingAdapted)
    {
        $this->buildingAdapted = $buildingAdapted;
    }

    /**
     * @var string
     */
    protected $buildingElevatornumber;

    /**
     * @var string
     */
    protected $additionalGarden;

    /**
     * @var string
     */
    protected $additionalBasement;

    /**
     * @var string
     */
    protected $buildingAdapted;

    /**
     * @return string
     */
    public function getAdditionalGarage()
    {
        return $this->additionalGarage;
    }

    /**
     * @param string $additionalGarage
     */
    public function setAdditionalGarage($additionalGarage)
    {
        $this->additionalGarage = $additionalGarage;
    }

    /**
     * @var string
     */
    protected $sortType;

    /**
     * @return string
     */
    public function getSortType()
    {
        return $this->sortType;
    }

    /**
     * @param string $sortType
     */
    public function setSortType($sortType)
    {
        $this->sortType = $sortType;
    }

    /**
     * @var string
     */
    protected $perPage = 12;

    /**
     * @return string
     */
    public function getPerPage($default = 12)
    {
        return $this->perPage ? $this->perPage : $default;
    }

    /**
     * @param string $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

}