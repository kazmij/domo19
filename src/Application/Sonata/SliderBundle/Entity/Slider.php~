<?php

namespace Application\Sonata\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\ClassificationBundle\Model\CategoryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Slider
 *
 * @ORM\Entity(repositoryClass="Application\Sonata\SliderBundle\Entity\SliderRepository")
 * @ORM\Table
 */
class Slider
{

    use \Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @var string
     */
    protected $placement;

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\SliderBundle\Entity\SliderItem", mappedBy="slider", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @var ArrayCollection of FeaturedItem
     */
    protected $items;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return Slider
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set placement.
     *
     * @param string $placement
     *
     * @return Slider
     */
    public function setPlacement($placement)
    {
        $this->placement = $placement;

        return $this;
    }

    /**
     * Get placement.
     *
     * @return string
     */
    public function getPlacement()
    {
        return $this->placement;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return Slider
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add item.
     *
     * @param \Application\Sonata\SliderBundle\Entity\SliderItem $item
     *
     * @return Slider
     */
    public function addItem(\Application\Sonata\SliderBundle\Entity\SliderItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item.
     *
     * @param \Application\Sonata\SliderBundle\Entity\SliderItem $item
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeItem(\Application\Sonata\SliderBundle\Entity\SliderItem $item)
    {
        return $this->items->removeElement($item);
    }

    /**
     * Get items.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * String representative
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() ? : '';
    }
}
