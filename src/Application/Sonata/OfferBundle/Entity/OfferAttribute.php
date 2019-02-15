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

//SELECT CONCAT('const ', UPPER(name), '_ATTRIBUTE_KEY = ', name, ';') FROM `classification__category` WHERE context = 'offer'

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
    const NAZWA_ATTRIBUTE_KEY = 'nazwa';
    const AUTOR_ATTRIBUTE_KEY = 'autor';
    const PRACOWNIA_ATTRIBUTE_KEY = 'pracownia';
    const PARTER_ATTRIBUTE_KEY = 'parter';
    const PODDASZE_ATTRIBUTE_KEY = 'poddasze';
    const PODDASZE_DO_ADAPTACJI_ATTRIBUTE_KEY = 'poddasze_do_adaptacji';
    const PIETRO_ATTRIBUTE_KEY = 'pietro';
    const PODPIWNICZENIE_ATTRIBUTE_KEY = 'podpiwniczenie';
    const POKOJE_ATTRIBUTE_KEY = 'pokoje';
    const LAZIENKI_ATTRIBUTE_KEY = 'lazienki';
    const KOMINEK_ATTRIBUTE_KEY = 'kominek';
    const PIWNICA_ATTRIBUTE_KEY = 'piwnica';
    const ENERGOOSZCZEDNY_ATTRIBUTE_KEY = 'energooszczedny';
    const GARAZ_ATTRIBUTE_KEY = 'garaz';
    const TECHNOLOGIA_ATTRIBUTE_KEY = 'technologia';
    const POW_CALKOWITA_ATTRIBUTE_KEY = 'pow_calkowita';
    const POW_UZYTKOWA_ATTRIBUTE_KEY = 'pow_uzytkowa';
    const POW_NETTO_ATTRIBUTE_KEY = 'pow_netto';
    const POW_ZABUDOWY_ATTRIBUTE_KEY = 'pow_zabudowy';
    const KUBATURA_ATTRIBUTE_KEY = 'kubatura';
    const POW_GARAZU_ATTRIBUTE_KEY = 'pow_garazu';
    const POW_DACHU_ATTRIBUTE_KEY = 'pow_dachu';
    const KAT_NACHYLENIA_DACHU_ATTRIBUTE_KEY = 'kat_nachylenia_dachu';
    const WYSOKOSC_BUDYNKU_ATTRIBUTE_KEY = 'wysokosc_budynku';
    const MIN_SZEROKOSC_DZIALKI_ATTRIBUTE_KEY = 'min_szerokosc_dzialki';
    const MIN_DLUGOSC_DZIALKI_ATTRIBUTE_KEY = 'min_dlugosc_dzialki';
    const RODZAJ_DACHU_ATTRIBUTE_KEY = 'rodzaj_dachu';
    const KOSZT_OGOLNY_ATTRIBUTE_KEY = 'koszt_ogolny';
    const KOSZT_SUROWY_ATTRIBUTE_KEY = 'koszt_surowy';
    const KOSZT_WYKONCZENIA_ATTRIBUTE_KEY = 'koszt_wykonczenia';
    const KOSZT_INSTALACJI_ATTRIBUTE_KEY = 'koszt_instalacji';
    const CENA_ATTRIBUTE_KEY = 'cena';
    const CENA_PROM_ATTRIBUTE_KEY = 'cena_prom';
    const OPIS_PROJEKTU_ATTRIBUTE_KEY = 'opis_projektu';
    const OPIS_TECHNOLOGIA_ATTRIBUTE_KEY = 'opis_technologia';
    const WYSOKOSC_POMIESZCZEN_ATTRIBUTE_KEY = 'wysokosc_pomieszczen';
    const RZUT_PARTER_ATTRIBUTE_KEY = 'rzut_parter';
    const RZUT_PODDASZE_ATTRIBUTE_KEY = 'rzut_poddasze';
    const RZUT_PIETRO_ATTRIBUTE_KEY = 'rzut_pietro';
    const RZUTY_INNE_ATTRIBUTE_KEY = 'rzuty_inne';
    const ID_KLIENTA_ATTRIBUTE_KEY = 'id_klienta';
    const POMIESZCZENIA_PARTER_ATTRIBUTE_KEY = 'pomieszczenia_parter';
    const POMIESZCZENIA_PODDASZE_ATTRIBUTE_KEY = 'pomieszczenia_poddasze';
    const WIZUALIZACJE_ATTRIBUTE_KEY = 'wizualizacje';
    const ELEWACJE_ATTRIBUTE_KEY = 'elewacje';
    const USYTUOWANIE_ATTRIBUTE_KEY = 'usytuowanie';
    const POMIESZCZENIA_INNE_ATTRIBUTE_KEY = 'pomieszczenia_inne';
    const POMIESZCZENIA_PIETRO_ATTRIBUTE_KEY = 'pomieszczenia_pietro';
    const GALLERY_ATTRIBUTE_KEY = 'pictures';

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
     * @ORM\JoinColumn(name="offer_id", referencedColumnName="id", nullable=false, onDelete="cascade")
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
