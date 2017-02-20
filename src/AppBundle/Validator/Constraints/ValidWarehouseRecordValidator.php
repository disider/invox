<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\WarehouseRecord;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidWarehouseRecordValidator extends ConstraintValidator
{
    /**
     * @param WarehouseRecord $warehouseRecord
     * @param ValidWarehouseRecord $constraint
     */
    public function validate($warehouseRecord, Constraint $constraint)
    {
        if (!($warehouseRecord->getLoadQuantity() || $warehouseRecord->getUnloadQuantity())) {
            $this->context->buildViolation('error.no_warehouse_quantities_defined')
                ->addViolation();
        }

        if ($warehouseRecord->getLoadQuantity() && !$warehouseRecord->getPurchasePrice()) {
            $this->context->buildViolation('error.empty_price')
                ->atPath('purchasePrice')
                ->addViolation();
        }

        if ($warehouseRecord->getUnloadQuantity() && !$warehouseRecord->getSellPrice()) {
            $this->context->buildViolation('error.empty_price')
                ->atPath('sellPrice')
                ->addViolation();
        }
    }
}
