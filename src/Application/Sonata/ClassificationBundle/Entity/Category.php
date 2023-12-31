<?php

namespace Application\Sonata\ClassificationBundle\Entity;

use Application\Sonata\OfferBundle\Admin\OfferImportAdmin;
use Application\Sonata\OfferBundle\Service\ImportOffers;
use Sonata\ClassificationBundle\Entity\BaseCategory;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 * References:
 * @link http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 */
class Category extends BaseCategory
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var integer $customKey
     */
    protected $customKey;

    /**
     * @var string $customName
     */
    protected $customName;

    /**
     * @var string $icon
     */
    protected $icon;


    /**
     * Get id.
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customKey.
     *
     * @param int|null $customKey
     *
     * @return Category
     */
    public function setCustomKey($customKey = null)
    {
        $this->customKey = $customKey;

        return $this;
    }

    /**
     * Get customKey.
     *
     * @return int|null
     */
    public function getCustomKey()
    {
        return $this->customKey;
    }

    /**
     * Set customName.
     *
     * @param string|null $customName
     *
     * @return Category
     */
    public function setCustomName($customName = null)
    {
        $this->customName = $customName;

        return $this;
    }

    /**
     * Get customName.
     *
     * @return string|null
     */
    public function getCustomName()
    {
        return $this->customName;
    }

    public function __toString()
    {
        if ($this->getDescription() && $this->getContext() && in_array($this->getContext()->getId(), [
                ImportOffers::CONTEXT_DEFINITIONS,
                ImportOffers::CONTEXT_ATTRIBUTES
            ])
        ) {

            return $this->getDescription();
        }

        return parent::__toString(); // TODO: Change the autogenerated stub
    }

    /**
     * Set icon.
     *
     * @param string|null $icon
     *
     * @return Category
     */
    public function setIcon($icon = null)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon.
     *
     * @return string|null
     */
    public function getIcon()
    {
        return $this->icon;
    }
}
