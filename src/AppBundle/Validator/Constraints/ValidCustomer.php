<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidCustomer extends Constraint
{
    public $unknownCustomerCodeMessage = 'error.unknown_customer_code';
    public $emptyNameMessage = 'error.empty_name';
    public $emptyVatNumberMessage = 'error.empty_vat_number';
    public $invalidVatNumberMessage = 'error.invalid_vat_number';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
