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

use Knp\DoctrineBehaviors\Model as ORMBehaviors;

class PaymentTypeTranslation extends BasePaymentTypeTranslation
{
    use ORMBehaviors\Translatable\Translation;
}