<?php

namespace Application\Sonata\OfferBundle\Entity;

use Application\Sonata\MainBundle\Model\RouteableInterface;
use Application\Sonata\MediaBundle\Entity\Gallery;
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

    const CATEGORY_PARTEROWE = 'parterowe';
    const CATEGORY_Z_PODDASZEM = 'z-poddaszem';
    const CATEGORY_PIETROWE = 'pietrowe';
    const CATEGORY_MALE = 'male';
    const CATEGORY_SREDNIE = 'srednie';
    const CATEGORY_DUZE = 'duze';
    const CATEGORY_TRADYCYJNE = 'tradycyjne';
    const CATEGORY_NOWOCZESNE = 'nowoczesne';
    const CATEGORY_WILLE = 'wille';
    const CATEGORY_DREWNIANE = 'drewniane';
    const CATEGORY_ZABUDOWA_BLIZNIACZA = 'zabudowa-blizniacza';
    const CATEGORY_TANIE_W_BUDOWIE = 'tanie-w-budowie';
    const CATEGORY_NA_WASKA_DZIALKE = 'na-waska-dzialke';

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
     * @ORM\ManyToMany(targetEntity="Application\Sonata\ClassificationBundle\Entity\Category", fetch="EXTRA_LAZY")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    /**
     * @Gedmo\Slug(fields={"externalId", "id"})
     * @ORM\Column(type="string")
     */
    protected $slug;

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getMainPhoto() {
        /* @var $gallery Gallery */
        $gallery = $this->getGallery();
        if($gallery && $gallery->getGalleryHasMedias()) {
            foreach($gallery->getGalleryHasMedias() as $galleryHasMedia) {
                if($galleryHasMedia->getMedia()->getCategory()) {
                    if($galleryHasMedia->getMedia()->getCategory()->getCustomName() == OfferAttribute::WIZUALIZACJE_ATTRIBUTE_KEY) {
                        return $galleryHasMedia->getMedia();
                    }
                }
            }

            return $gallery->getGalleryHasMedias()->last()->getMedia();
        }

        return false;
    }


    public function getRouteName()
    {

        return self::ROUTE_NAME;
    }

    public function getRouteParams()
    {
        return [
            'slug' => $this->getSlug()
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

    /**
     * @param $name
     * @param $arguments
     * @return bool
     * @throws \ReflectionException
     */
    public function __call($name, $arguments)
    {
        if(preg_match('/^get[A-Z]+/', $name)) {
            $attributeNames = preg_split('/(?<=\\w)(?=[A-Z])/', str_replace('get', '', $name));
            $constantName = strtoupper(implode('_', $attributeNames)) . '_ATTRIBUTE_KEY';
            $reflect = new \ReflectionClass(OfferAttribute::class);
            if(array_key_exists($constantName, $reflect->getConstants())) {
                return $this->getAttributeValue($reflect->getConstant($constantName));
            }
        }

        return false;
    }

    /**
     * Add category.
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $category
     *
     * @return Offer
     */
    public function addCategory(\Application\Sonata\ClassificationBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \Application\Sonata\ClassificationBundle\Entity\Category $category
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategory(\Application\Sonata\ClassificationBundle\Entity\Category $category)
    {
        return $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
