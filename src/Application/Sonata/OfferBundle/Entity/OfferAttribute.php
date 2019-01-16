<?php

namespace Application\Sonata\OfferBundle\Entity;

use Application\Sonata\ClassificationBundle\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Offer
 *
 * @ORM\Entity(repositoryClass="Application\Sonata\OfferBundle\Entity\OfferAttributeRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"attribute_custom_value"}, flags={"fulltext"})})
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 *
 */
class OfferAttribute
{

    const GALLERY_ATTRIBUTE_KEY = 'pictures';
    const LOCATION_CITY_KEY = 'locationCityName';
    const LOCATION_PRECINCT_KEY = 'locationPrecinctName';
    const LOCATION_PROVINCE_KEY = 'locationProvinceName';
    const LOCATION_STREET_KEY = 'locationStreetName';
    const PRICE_KEY = 'price';
    const PRICE_CURRENCY_KEY = 'priceCurrency';
    const PRICE_PER_METER_KEY = 'pricePermeter';
    const ROOMS_NUMBER_KEY = 'apartmentRoomNumber';
    const AREA_SIZE_TOTAL_KEY = 'areaTotal';
    const SYSTEM_UPDATE_DATE_KEY = 'updateDate';
    const FLOOR_KEY = 'buildingFloornumber';
    const BUILDING_YEAR_KEY = 'buildingYear';
    const ADDITIONAL_GARAGE_KEY = 'additionalGarage';
    const ADDITIONAL_BALCONY_KEY = 'additionalBalcony';
    const ADDITIONAL_ELEVATOR_KEY = 'buildingElevatornumber';
    const ADDITIONAL_BASEMENT_KEY = 'additionalBasement';
    const ADDITIONAL_STORAGE_KEY = 'additionalStorage';
    const ADDITIONAL_GARDEN_KEY = 'additionalGarden';
    const ADDITIONAL_ADAPTED_KEY = 'buildingAdapted';
    const LOCATION_STREET_TYPE_KEY = 'locationStreetType';
    const ADDITIONAL_PARKING_KEY = 'additionalParking';
    const OFFER_NUMBER_KEY = 'number';
    const TYPE_NAME = 'typeName';
    const APARTMENT_FLOOR_KEY = 'apartmentFloor';
    const ROOM_DESCRIPTION_KEy = 'descriptionRoom';
    const APARTMENT_STATUS_KEY = 'buildingCondition';
    const APARTMENT_OWNERSHIP_KEY = 'apartmentOwnership';
    const BUILDING_MATERIAL_KEY = 'buildingMaterials';
    const AVAILABLE_DATE_KEY = 'availableDate';
    const DESCRIPTION_KEY = 'description';
    const DESCRIPTION_WEBSITE_KEY = 'descriptionWebsite';
    const LATITUDE_KEY = 'locationLatitude';
    const LONGITUDE_KEY = 'locationLongitude';
    const OFFER_TITLE_KEY = 'portalTitle';
    const OFFER_ACTION = 'action';
    const CONTACT_EMAIL_KEY = 'contactEmail';
    const CONTACT_NAME_KEY = 'contactFirstname';
    const CONTACT_LASTNAME_KEY = 'contactLastname';
    const CONTACT_PHONE_KEY = 'contactPhone';


    use \Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\OfferBundle\Entity\Offer", inversedBy="attributes", fetch="EXTRA_LAZY")
     * @var Offer
     */
    protected $offer;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\ClassificationBundle\Entity\Category", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var Category
     */
    protected $attribute;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\ClassificationBundle\Entity\Category", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="attribute_value_id", referencedColumnName="id")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var Category
     */
    protected $attributeValue;

    /**
     * @ORM\Column(name="attribute_custom_value", type="text", nullable=true)
     * @var string
     */
    protected $value;

    /**
     * @ORM\Column(name="attribute_custom_value_integer", type="float", nullable=true)
     * @var string
     */
    protected $valueNumeric;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Gallery", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var ArrayCollection
     */
    protected $gallery;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set offer.
     *
     * @param \Application\Sonata\OfferBundle\Entity\Offer|null $offer
     *
     * @return OfferAttribute
     */
    public function setOffer(\Application\Sonata\OfferBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer.
     *
     * @return \Application\Sonata\OfferBundle\Entity\Offer|null
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set value.
     *
     * @param string|null $value
     *
     * @return OfferAttribute
     */
    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attribute.
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category|null $attribute
     *
     * @return OfferAttribute
     */
    public function setAttribute(\Application\Sonata\ClassificationBundle\Entity\Category $attribute = null)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @return \Application\Sonata\ClassificationBundle\Entity\Category|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set attributeValue.
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category|null $attributeValue
     *
     * @return OfferAttribute
     */
    public function setAttributeValue(\Application\Sonata\ClassificationBundle\Entity\Category $attributeValue = null)
    {
        $this->attributeValue = $attributeValue;

        return $this;
    }

    /**
     * Get attributeValue.
     *
     * @return \Application\Sonata\ClassificationBundle\Entity\Category|null
     */
    public function getAttributeValue()
    {
        return $this->attributeValue;
    }

    /**
     * Set gallery.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Gallery|null $gallery
     *
     * @return OfferAttribute
     */
    public function setGallery(\Application\Sonata\MediaBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Gallery|null
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @return null|string
     */
    public function getVal()
    {
        if ($this->getAttributeValue()) {
            return $this->getAttributeValue()->getName();
        } else {
            return $this->getValueNumeric() ? $this->getValueNumeric() : $this->getValue();
        }
    }

    /**
     * Set valueNumeric.
     *
     * @param int|null $valueNumeric
     *
     * @return OfferAttribute
     */
    public function setValueNumeric($valueNumeric = null)
    {
        $this->valueNumeric = $valueNumeric;

        return $this;
    }

    /**
     * Get valueNumeric.
     *
     * @return int|null
     */
    public function getValueNumeric()
    {
        return $this->valueNumeric;
    }
}
