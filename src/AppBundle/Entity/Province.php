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
class Province
{
    /** @var int */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_name")
     *
     * @var string
     */
    private $name;

    /**
     * @Assert\NotBlank(message="error.empty_code")
     *
     * @JMS\Expose
     * @var string
     */
    private $code;

    /**
     * @Assert\NotBlank(message="error.empty_country")
     * @var Country 
     */
    private $country;

    /** @var ArrayCollection */
    private $cities;

    public static function create(Country $country, $name, $code)
    {
        $entity = new self();
        $entity->country = $country;
        $entity->name = $name;
        $entity->code = $code;

        return $entity;
    }

    public function __construct()
    {
        $this->cities = new ArrayCollection();
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCities()
    {
        return $this->cities;
    }

    public function setCities($cities)
    {
        $this->cities = $cities;
    }

    public function addCity($city)
    {
        $city->setProvince($this);
        $this->cities->add($city);
    }

    /** @JMS\VirtualProperty */
    public function getCountryId()
    {
        return $this->getCountry()->getId();
    }
}
