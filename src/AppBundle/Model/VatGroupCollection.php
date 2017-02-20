<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Model;

use AppBundle\Entity\DocumentRow;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class VatGroupCollection
{
    /** @var ArrayCollection */
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    public function addRow(DocumentRow $row)
    {
        $group = $this->getGroup($row->getTaxRateAmount());

        $group->addRow($row);
    }

    public function removeRow(DocumentRow $row)
    {
        $group = $this->getGroup($row->getTaxRateAmount());

        $group->removeRow($row);

        if ($group->isEmpty()) {
            $this->groups->removeElement($group);
        }
    }

    public function getGroups()
    {
        $criteria = Criteria::create()
            ->orderBy(['taxRate' => Criteria::ASC]);

        return $this->groups->matching($criteria);
    }

    private function getGroup($vat)
    {
        /** @var VatGroup $group */
        foreach ($this->groups as $group) {
            if ($group->getTaxRate() == $vat) {
                return $group;
            }
        }

        $group = new VatGroup($vat);
        $this->groups->add($group);

        return $group;
    }

    public function hasGroups()
    {
        return $this->groups->count() > 0;
    }

    public function getTaxesTotal()
    {
        $total = 0;

        /** @var VatGroup $group */
        foreach ($this->groups as $group) {
            $total += $group->getTaxesTotal();
        }

        return $total;
    }
}

