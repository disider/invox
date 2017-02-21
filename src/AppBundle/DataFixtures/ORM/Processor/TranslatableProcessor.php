<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\DataFixtures\ORM\Processor;

use AppBundle\Entity\TaxRate;
use Nelmio\Alice\ProcessorInterface;

class TranslatableProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess($object)
    {
        if (!($object instanceof TaxRate)) {
            return;
        }

        /** @var TaxRate $object */
        $object->mergeNewTranslations();
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess($object)
    {
    }
}