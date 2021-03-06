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

class PaymentType extends BasePaymentType
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @Assert\Valid
     */
    protected $translations;

    public static function create($position, $name, $days, $endOfMonth)
    {
        $entity = new self();
        $entity->setPosition($position);
        $entity->setName($name);
        $entity->setDays($days);
        $entity->setEndOfMonth($endOfMonth);

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
        return (string)$this->proxyCurrentLocaleTranslation('getName');
    }

}
