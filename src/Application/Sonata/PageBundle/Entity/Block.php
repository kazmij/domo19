<?php

namespace Application\Sonata\PageBundle\Entity;

use Sonata\PageBundle\Entity\BaseBlock as BaseBlock;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * This file has been generated by the SonataEasyExtendsBundle.
 *
 * @link https://sonata-project.org/easy-extends
 *
 * References:
 * @link http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 */
class Block extends BaseBlock
{
    /**
     * @var int $id
     */
    protected $id;

    protected $settings = [];

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
     * @ORM\Column(type="string", length=50, unique=true, nullable=true)
     * @Gedmo\Slug(style="camel", separator="_", prefix="", suffix="", updatable=false, unique=true)
     * @var string
     */
    protected $alias;

    /**
     * Add child.
     *
     * @param \Application\Sonata\PageBundle\Entity\Block $child
     *
     * @return Block
     */
    public function addChild(\Application\Sonata\PageBundle\Entity\Block $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \Application\Sonata\PageBundle\Entity\Block $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\Application\Sonata\PageBundle\Entity\Block $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Set alias.
     *
     * @param string|null $alias
     *
     * @return Block
     */
    public function setAlias($alias = null)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string|null
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->settings ?  $this->settings : [];
    }

    /**
     * {@inheritdoc}
     */
    public function setData($settings = [])
    {
        if($settings == null) {
            $settings = [];
        }

        $this->settings = $settings;
    }
}
