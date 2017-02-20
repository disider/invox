<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Form\Transformer;

use AppBundle\Entity\PettyCashNote;
use Symfony\Component\Form\DataTransformerInterface;

class PettyCashNoteTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        $transferNote = $value->getTransferNote();
        
        if(!$transferNote) {
            $transferNote = new PettyCashNote();
            $transferNote->setRef($value->getRef());
            $transferNote->setCompany($value->getCompany());
            $value->setTransferNote($transferNote);
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $transferNote = $value->getTransferNote();

        if($transferNote->getAccount() == null) {
            $value->setTransferNote(null);
            $transferNote->setTransferNote(null);
        }
        else {
            $transferNote->setAmount(-$value->getAmount());
        }

        return $value;
    }
}