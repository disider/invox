<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\DataFixtures\ORM\Processor;

use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentRow;
use Nelmio\Alice\ProcessorInterface;

class DocumentProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess($object)
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
    public function postProcess($object)
    {
    }
}