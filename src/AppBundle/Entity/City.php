<?php
/**
 * This file is part of the Invox.
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
class City
{
    /** @var int */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @JMS\Expose
     * @var string
     */
    private $name;

    /**
     * @JMS\Expose
     * @Assert\NotBlank(message="error.empty_province")
     * @var Province
     */
    private $province;

    /**
     * @var ArrayCollection
     */
    private $zipCodes;

    public static function create(Province $province, $name)
    {
        $entity = new self();
        $entity->province = $province;
        $entity->name = $name;

        return $entity;
    }

    public function __construct()
    {
        $this->zipCodes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function setProvince($province)
    {
        $this->province = $province;
    }

    public function getZipCodes()
    {
        return $this->zipCodes;
    }

    public function setZipCodes($zipCodes)
    {
        $this->zipCodes = $zipCodes;
    }

    public function addZipCode($zipCode)
    {
        $zipCode->setCity($this);
        $this->zipCodes->add($zipCode);
    }
}
