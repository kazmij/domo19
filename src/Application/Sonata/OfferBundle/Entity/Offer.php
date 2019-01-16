<?php

namespace Application\Sonata\OfferBundle\Entity;

use Application\Sonata\MainBundle\Model\RouteableInterface;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Offer
 *
 * @ORM\Entity(repositoryClass="Application\Sonata\OfferBundle\Entity\OfferRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"search_phrase"}, flags={"fulltext"})})
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Offer implements RouteableInterface
{

    const ROUTE_NAME = 'application_sonata_offer_view';

    const CATEGORIES_CONTEXT = 'project_categories';

    use \Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\OfferBundle\Entity\OfferAttribute", mappedBy="offer", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var ArrayCollection of OfferAttribute
     */
    protected $attributes;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="creator_user_id", referencedColumnName="id")
     * @var User
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="updated_user_id", referencedColumnName="id")
     * @var User
     */
    protected $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="assigned_user_id", referencedColumnName="id")
     * @var User
     */
    protected $assignedTo;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Application\Sonata\OfferBundle\Entity\OfferImport", mappedBy="offers", fetch="EXTRA_LAZY")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var OfferImport
     */
    protected $imports;

    /**
     * @ORM\Column(name="external_id", type="string", length=30, nullable=true)
     * @var string
     */
    protected $externalId;

    /**
     * @ORM\Column(name="search_phrase", type="text", nullable=true)
     * @var string
     */
    protected $searchPhrase;

    /**
     * @ORM\Column(name="updates", type="integer", nullable=true)
     * @var string
     */
    protected $updates;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add attribute.
     *
     * @param \Application\Sonata\OfferBundle\Entity\OfferAttribute $attribute
     *
     * @return Offer
     */
    public function addAttribute(\Application\Sonata\OfferBundle\Entity\OfferAttribute $attribute)
    {
        $this->attributes[] = $attribute;

        return $this;
    }

    /**
     * Remove attribute.
     *
     * @param \Application\Sonata\OfferBundle\Entity\OfferAttribute $attribute
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAttribute(\Application\Sonata\OfferBundle\Entity\OfferAttribute $attribute)
    {
        return $this->attributes->removeElement($attribute);
    }

    /**
     * Get attributes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set createdBy.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $createdBy
     *
     * @return Offer
     */
    public function setCreatedBy(\Application\Sonata\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $updatedBy
     *
     * @return Offer
     */
    public function setUpdatedBy(\Application\Sonata\UserBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set assignedTo.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $assignedTo
     *
     * @return Offer
     */
    public function setAssignedTo(\Application\Sonata\UserBundle\Entity\User $assignedTo = null)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo.
     *
     * @return \Application\Sonata\UserBundle\Entity\User|null
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Set externalId.
     *
     * @param string|null $externalId
     *
     * @return Offer
     */
    public function setExternalId($externalId = null)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId.
     *
     * @return string|null
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set import.
     *
     * @param \Application\Sonata\OfferBundle\Entity\OfferImport|null $import
     *
     * @return Offer
     */
    public function setImport(\Application\Sonata\OfferBundle\Entity\OfferImport $import = null)
    {
        $this->import = $import;

        return $this;
    }

    /**
     * Get import.
     *
     * @return \Application\Sonata\OfferBundle\Entity\OfferImport|null
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * Add import.
     *
     * @param \Application\Sonata\OfferBundle\Entity\OfferImport $import
     *
     * @return Offer
     */
    public function addImport(\Application\Sonata\OfferBundle\Entity\OfferImport $import)
    {
        if ($this->imports) {
            if (!$this->imports->contains($import)) {
                $this->imports[] = $import;
            }
        } else {
            $this->imports[] = $import;
        }

        return $this;
    }

    /**
     * Remove import.
     *
     * @param \Application\Sonata\OfferBundle\Entity\OfferImport $import
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImport(\Application\Sonata\OfferBundle\Entity\OfferImport $import)
    {
        return $this->imports->removeElement($import);
    }

    /**
     * Get imports.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function getAttribute($key)
    {
        $attributes = $this->getAttributes()->filter(function ($entry) use ($key) {
            return $entry->getAttribute() ? $entry->getAttribute()->getName() == $key : false;
        });

        if ($attributes->count()) {
            return $attributes->first();
        }

        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function getAttributeValue($key)
    {
        $attribute = $this->getAttribute($key);

        if ($attribute) {
            return $attribute->getVal();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getGallery()
    {
        $galleryAttributeKey = OfferAttribute::GALLERY_ATTRIBUTE_KEY;
        $galleryAttribute = $this->getAttribute($galleryAttributeKey);

        if ($galleryAttribute) {
            return $galleryAttribute->getGallery();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function getCity()
    {
        return $this->getAttributeValue(OfferAttribute::LOCATION_CITY_KEY);
    }

    public function getPrecinct()
    {
        return $this->getAttributeValue(OfferAttribute::LOCATION_PRECINCT_KEY);
    }

    public function getProvince()
    {
        return $this->getAttributeValue(OfferAttribute::LOCATION_PROVINCE_KEY);
    }

    public function getStreet()
    {
        return $this->getAttributeValue(OfferAttribute::LOCATION_STREET_KEY);
    }

    public function getStreetType()
    {
        return $this->getAttributeValue(OfferAttribute::LOCATION_STREET_TYPE_KEY);
    }

    public function getPrice()
    {
        return (float)$this->getAttributeValue(OfferAttribute::PRICE_KEY);
    }

    public function getPriceCurrency()
    {
        $currency = $this->getAttributeValue(OfferAttribute::PRICE_CURRENCY_KEY);

        return $currency ? strtoupper($currency) : 'PLN';
    }

    public function getPricePerMeter()
    {
        return (float)$this->getAttributeValue(OfferAttribute::PRICE_PER_METER_KEY);
    }

    public function getRoomsNumber()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ROOMS_NUMBER_KEY);
    }

    public function getAreaTotal()
    {
        return (int)$this->getAttributeValue(OfferAttribute::AREA_SIZE_TOTAL_KEY);
    }

    public function getOfferNumber()
    {
        return trim($this->getAttributeValue(OfferAttribute::OFFER_NUMBER_KEY));
    }

    public function getTypeName()
    {
        return $this->getAttributeValue(OfferAttribute::TYPE_NAME);
    }

    public function getFloorsNumber()
    {
        return $this->getAttributeValue(OfferAttribute::FLOOR_KEY);
    }

    public function getFloorNumber()
    {
        return $this->getAttributeValue(OfferAttribute::APARTMENT_FLOOR_KEY);
    }

    public function getDescriptionRoom()
    {
        return $this->getAttributeValue(OfferAttribute::ROOM_DESCRIPTION_KEy);
    }

    public function getStatus()
    {
        return $this->getAttributeValue(OfferAttribute::APARTMENT_STATUS_KEY);
    }

    public function getOwnership()
    {
        return $this->getAttributeValue(OfferAttribute::APARTMENT_OWNERSHIP_KEY);
    }

    public function getBuildingYear()
    {
        return $this->getAttributeValue(OfferAttribute::BUILDING_YEAR_KEY);
    }

    public function getBuildingMaterial()
    {
        return $this->getAttributeValue(OfferAttribute::BUILDING_MATERIAL_KEY);
    }

    public function getElevatorNumber()
    {
        return $this->getAttributeValue(OfferAttribute::ADDITIONAL_ELEVATOR_KEY);
    }

    public function getAdditionalGarageNumber()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_GARAGE_KEY);
    }

    public function getAdditionalBasement()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_BASEMENT_KEY);
    }

    public function getAdditionalBalcony()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_BALCONY_KEY);
    }

    public function getAdditionalGarden()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_GARDEN_KEY);
    }

    public function getAdditionalStorage()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_STORAGE_KEY);
    }

    public function getAdditionalParking()
    {
        return (int)$this->getAttributeValue(OfferAttribute::ADDITIONAL_PARKING_KEY);
    }

    public function getAvailableDate()
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getAttributeValue(OfferAttribute::AVAILABLE_DATE_KEY));

        return $date;
    }

    public function getDescription()
    {
        return $this->getAttributeValue(OfferAttribute::DESCRIPTION_WEBSITE_KEY);
    }

    public function getLatitude()
    {
        return $this->getAttributeValue(OfferAttribute::LATITUDE_KEY);
    }

    public function getLongitude()
    {
        return $this->getAttributeValue(OfferAttribute::LONGITUDE_KEY);
    }

    public function getOfferTitle()
    {
        $title = $this->getAttributeValue(OfferAttribute::DESCRIPTION_WEBSITE_KEY);
        if (!trim($title)) {
            $street = $this->getStreetType() ? ($this->getStreetType() . ' ' . $this->getStreet()) : ('ul. ' . $this->getStreet());
            $names = array_filter([$this->getCity(), $this->getPrecinct(), $street, $this->getProvince()]);
            $title = implode(', ', $names);
        }

        return $title;
    }

    public function getOfferName()
    {
        $street = $this->getStreetType() ? ($this->getStreetType() . ' ' . $this->getStreet()) : ('ul. ' . $this->getStreet());
        $names = array_filter([$this->getCity(), $this->getPrecinct(), $street, $this->getProvince()]);
        $title = implode(', ', $names);

        return $title;
    }

    public function getOfferFullName()
    {
        $street = $this->getStreetType() ? ($this->getStreetType() . ' ' . $this->getStreet()) : ('ul. ' . $this->getStreet());
        $names = array_filter([$this->getTypeName(), $this->getCity(), $this->getPrecinct(), $street, $this->getProvince()]);
        $title = implode(', ', $names);

        return $title;
    }

    public function getContactFullName()
    {
        $fullName = '';
        $firstName = $this->getAttributeValue(OfferAttribute::CONTACT_NAME_KEY);
        $lastName = $this->getAttributeValue(OfferAttribute::CONTACT_LASTNAME_KEY);

        if ($firstName || $lastName) {
            return trim($firstName . ' ' . $lastName);
        }

        return $fullName;
    }

    public function getContactEmail()
    {
        return $this->getAttributeValue(OfferAttribute::CONTACT_EMAIL_KEY);
    }

    public function getContactPhone()
    {
        $phone = $this->getAttributeValue(OfferAttribute::CONTACT_PHONE_KEY);

        if ($phone) {
            if (preg_match('/^0048/', $phone)) {
                $phone = preg_replace('/^0048/', '+48 ', $phone);
            } elseif (!preg_match('/^\+48/', $phone)) {
                $phone = $phone = '+48 ' . $phone;
            } else {
                $phone = '+' . $phone;
            }
        }

        return $phone;
    }

    public function getLastUpdatedDate()
    {
        $dateString = $this->getAttributeValue(OfferAttribute::SYSTEM_UPDATE_DATE_KEY);

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $dateString);

        if (!$date) {
            $date = $this->getUpdatedAt();
        }

        return $date;
    }

    public function getRouteName()
    {

        return self::ROUTE_NAME;
    }

    public function getRouteParams()
    {
        return [
            'id' => $this->getId()
        ];
    }

    /**
     * Set searchPhrase.
     *
     * @param string|null $searchPhrase
     *
     * @return Offer
     */
    public function setSearchPhrase($searchPhrase = null)
    {
        $this->searchPhrase = $searchPhrase;

        return $this;
    }

    /**
     * Get searchPhrase.
     *
     * @return string|null
     */
    public function getSearchPhrase()
    {
        return $this->searchPhrase;
    }

    /**
     * Set updates.
     *
     * @param int|null $updates
     *
     * @return Offer
     */
    public function setUpdates($updates = null)
    {
        $this->updates = $updates;

        return $this;
    }

    /**
     * Get updates.
     *
     * @return int|null
     */
    public function getUpdates()
    {
        return $this->updates;
    }
}
