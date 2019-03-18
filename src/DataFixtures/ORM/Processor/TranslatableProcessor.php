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

use App\Entity\TaxRate;
use Fidry\AliceDataFixtures\ProcessorInterface;

class TranslatableProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $id, $object): void
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
    public function postProcess(string $id, $object): void
    {
    }
}