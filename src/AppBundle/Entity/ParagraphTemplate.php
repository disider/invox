<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @CustomAssert\ValidParagraphTemplate()
 * @JMS\ExclusionPolicy("all")
 */
class ParagraphTemplate
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_title")
     * @JMS\Expose()
     * @var string
     */
    private $title;

    /**
     * @JMS\Expose()
     * @var string
     */
    private $description = '';

    /**
     * @Assert\Valid
     * @JMS\Expose()
     * @var ArrayCollection
     */
    private $children;

    /**
     * @var Paragraph
     */
    private $parent;

    /**
     * @var Company
     */
    private $company;

    public static function create(Company $company, $title, $description)
    {
        $entity = new self();
        $entity->setCompany($company);
        $entity->setTitle($title);
        $entity->setDescription($description);

        return $entity;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild(ParagraphTemplate $child)
    {
        $this->children->add($child);

        $child->setParent($this);
    }

    public function removeChild(ParagraphTemplate $child)
    {
        $this->children->removeElement($child);
    }

    public function setParent(ParagraphTemplate $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function hasParent()
    {
        return $this->parent != null;
    }

    public function hasCompany()
    {
        return $this->company != null;
    }
}
