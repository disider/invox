<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\DataFixtures\ORM\Processor;

use App\Entity\Document;
use App\Entity\DocumentRow;
use Fidry\AliceDataFixtures\ProcessorInterface;

class DocumentProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $id, $object): void
    {
        if (!($object instanceof Document || $object instanceof DocumentRow)) {
            return;
        }

        if ($object instanceof Document) {
            $object->copyCustomerDetails();
        }

        $object->calculateTotals();
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $id, $object): void
    {
    }
}