<?php

namespace Application\Sonata\OfferBundle\Entity;

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
 * @ORM\Entity(repositoryClass="Application\Sonata\OfferBundle\Entity\OfferImportRepository")
 * @ORM\Table
 */
class OfferImport
{
    const MAX_ERRORS_COUNT = 10;

    use \Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="creator_user_id", referencedColumnName="id")
     * @var User
     */
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="assigned_user_id", referencedColumnName="id")
     * @var User
     */
    protected $assignedTo;


    /**
     * @ORM\ManyToMany(targetEntity="Application\Sonata\OfferBundle\Entity\Offer", inversedBy="imports", fetch="EXTRA_LAZY", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="offer_to_import",
     *      joinColumns={@ORM\JoinColumn(name="import_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="offer_id", referencedColumnName="id", onDelete="cascade")}
     *      )
     *
     * @var ArrayCollection of Offer
     */
    protected $offers;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="import_file_id", referencedColumnName="id", onDelete="SET NULL")
     * @var Media
     */
    protected $importFile;

    /**
     * @ORM\Column(name="status", type="string", length=30, nullable=true)
     * @var string
     */
    protected $status = 'new';

    /**
     * @ORM\Column(name="error", type="text", nullable=true)
     * @var string
     */
    protected $error;

    /**
     * @ORM\Column(name="errorsCount", type="integer", nullable=true)
     * @var string
     */
    protected $errorsCount = 0;

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
     * Set createdBy.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $createdBy
     *
     * @return OfferImport
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
     * Set assignedTo.
     *
     * @param \Application\Sonata\UserBundle\Entity\User|null $assignedTo
     *
     * @return OfferImport
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
     * Set importFile.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media|null $importFile
     *
     * @return OfferImport
     */
    public function setImportFile(\Application\Sonata\MediaBundle\Entity\Media $importFile = null)
    {
        $this->importFile = $importFile;

        return $this;
    }

    /**
     * Get importFile.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media|null
     */
    public function getImportFile()
    {
        return $this->importFile;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return OfferImport
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set error.
     *
     * @param string|null $error
     *
     * @return OfferImport
     */
    public function setError($error = null)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error.
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add offer.
     *
     * @param \Application\Sonata\OfferBundle\Entity\Offer $offer
     *
     * @return OfferImport
     */
    public function addOffer(\Application\Sonata\OfferBundle\Entity\Offer $offer)
    {
        if($this->offers) {
            if(!$this->offers->contains($offer)) {
                $this->offers[] = $offer;
            }
        } else {
            $this->offers[] = $offer;
        }

        return $this;
    }

    /**
     * Remove offer.
     *
     * @param \Application\Sonata\OfferBundle\Entity\Offer $offer
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOffer(\Application\Sonata\OfferBundle\Entity\Offer $offer)
    {
        return $this->offers->removeElement($offer);
    }

    /**
     * Get offers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffers()
    {
        return $this->offers;
    }

    public function getOffersCount() {

        return count($this->getOffers());
    }

    /**
     * Set errorsCount.
     *
     * @param int|null $errorsCount
     *
     * @return OfferImport
     */
    public function setErrorsCount($errorsCount = 0)
    {
        $this->errorsCount = $errorsCount;

        return $this;
    }

    /**
     * Get errorsCount.
     *
     * @return int|null
     */
    public function getErrorsCount()
    {
        return $this->errorsCount;
    }
}
