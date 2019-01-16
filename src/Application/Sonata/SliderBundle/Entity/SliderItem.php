<?php

namespace Application\Sonata\SliderBundle\Entity;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Model\MediaInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * SliderItem
 *
 * @ORM\Entity
 * @ORM\Table
 */
class SliderItem
{

    use \Gedmo\Timestampable\Traits\TimestampableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lead;

    /**
     * @var MediaInterface
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     */
    protected $image;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\SliderBundle\Entity\Slider", inversedBy="items")
     * @var SliderItem
     */
    protected $slider;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $startDate;

    /**
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $endDate;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    protected $enabled = false;


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
     * Set url.
     *
     * @param string $url
     *
     * @return SliderItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return SliderItem
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
     * Set lead.
     *
     * @param string|null $lead
     *
     * @return SliderItem
     */
    public function setLead($lead = null)
    {
        $this->lead = $lead;

        return $this;
    }

    /**
     * Get lead.
     *
     * @return string|null
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Set position.
     *
     * @param int $position
     *
     * @return SliderItem
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return SliderItem
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime|null $endDate
     *
     * @return SliderItem
     */
    public function setEndDate($endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return SliderItem
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
     * Set image.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media|null $image
     *
     * @return SliderItem
     */
    public function setImage(\Application\Sonata\MediaBundle\Entity\Media $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set slider.
     *
     * @param \Application\Sonata\SliderBundle\Entity\Slider|null $slider
     *
     * @return SliderItem
     */
    public function setSlider(\Application\Sonata\SliderBundle\Entity\Slider $slider = null)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * Get slider.
     *
     * @return \Application\Sonata\SliderBundle\Entity\Slider|null
     */
    public function getSlider()
    {
        return $this->slider;
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
