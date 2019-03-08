<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidRecurrence extends Constraint
{
    public $selectCustomerMessage = 'error.invalid_select_customer';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
