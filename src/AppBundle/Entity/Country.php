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
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

class Country
{
    use ORMBehaviors\Translatable\Translatable;

    /** @var int */
    private $id;

    /**
     * @Assert\NotBlank(message="error.empty_code")
     *
     * @var string
     */
    private $code;

    /** @var ArrayCollection */
    private $provinces;

    public static function create($code, $name = null)
    {
        $entity = new self();
        $entity->code = $code;

        if (!$name) {
            $name = $code;
        }
        $entity->setName($name);

        return $entity;
    }

    public function __construct()
    {
        $this->provinces = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->proxyCurrentLocaleTranslation('getName');
    }

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name, $locale = 'en')
    {
        $this->translate($locale)->setName($name);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getProvinces()
    {
        return $this->provinces;
    }

    public function setProvinces($provinces)
    {
        $this->provinces = $provinces;
    }

    public function addProvince($province)
    {
        $province->setCountry($this);
        $this->provinces->add($province);
    }

}
