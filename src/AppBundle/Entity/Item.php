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

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class Item extends AttachmentContainer implements Taggable
{
    /**
     * @JMS\Expose
     * @var int
     */
    protected $id;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @JMS\Expose
     * @var string
     */
    protected $name;

    /**
     * @Assert\NotBlank(message="error.empty_code")
     *
     * @JMS\Expose
     * @var string
     */
    protected $code;

    /**
     * @Assert\NotBlank(message="error.empty_unit_price")
     * @var float
     */
    protected $unitPrice;

    /**
     * @var string
     */
    protected $measureUnit;

    /**
     * @var TaxRate
     */
    protected $taxRate;

    /**
     * @var ArrayCollection
     */
    protected $tags;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $internalNotes;

    public function __construct()
    {
        parent::__construct();

        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ? $this->name : '';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    public function getMeasureUnit()
    {
        return $this->measureUnit;
    }

    public function setMeasureUnit($measureUnit)
    {
        $this->measureUnit = $measureUnit;
    }

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $tags = preg_split('/\s*,\s*/', trim($tags), null, PREG_SPLIT_NO_EMPTY);

        $tagsToRemove = [];

        foreach ($this->getTags() as $tag) {
            $found = false;

            $i = 0;

            foreach ($tags as $i => $name) {
                if ($tag->getName() == $name) {
                    $found = true;
                }
                break;
            }

            if ($found) {
                unset($tags[$i]);
            } else {
                $tagsToRemove[] = $tag;
            }
        }

        foreach ($tags as $name) {
            $tag = $this->buildTag();
            $tag->setName($name);
            $tag->setTaggable($this);
            $this->tags->add($tag);
        }

        foreach ($tagsToRemove as $tag) {
            $this->tags->removeElement($tag);
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getInternalNotes()
    {
        return $this->internalNotes;
    }

    public function setInternalNotes($internalNotes)
    {
        $this->internalNotes = $internalNotes;
    }

    public function getUploadDir()
    {
        return $this->getCompany()->getUploadDir();
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("unitPrice")
     *
     * @return string
     */
    public function serializeUnitPrice()
    {
        return $this->unitPrice ?: 0;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("taxRate")
     *
     * @return string
     */
    public function serializeTaxRate()
    {
        return $this->taxRate ? $this->taxRate->getId() : '';
    }

    protected abstract function buildTag();

}
