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

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
class ZipCode
{
    /**
     * @JMS\Expose
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_code")
     *
     * @JMS\Expose
     * @var string
     */
    private $code;

    /**
     * @Assert\NotBlank(message="error.empty_city")
     * @JMS\Expose
     * @var City
     */
    private $city;

    public static function create(City $city, $code)
    {
        $entity = new self();
        $entity->city = $city;
        $entity->code = $code;

        return $entity;
    }

    public function __toString()
    {
        return $this->code;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

}
