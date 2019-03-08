<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

class TaxRate
{
    use ORMBehaviors\Translatable\Translatable;

    /** @var int */
    private $id;

    /** @var integer */
    private $position;

    /**
     * @Assert\NotBlank(message="error.empty_amount")
     *
     * @var float
     */
    private $amount;

    public static function create($name, $amount)
    {
        $entity = new self();
        $entity->setPosition(0);
        $entity->setAmount($amount);
        $entity->setName($name);

        return $entity;
    }

    public static function createEmpty($position)
    {
        $entity = new self();
        $entity->setPosition($position);

        return $entity;
    }

    public function __toString()
    {
        return $this->proxyCurrentLocaleTranslation('getName');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setName($name, $locale = 'en')
    {
        $this->translate($locale)->setName($name);
    }

}
