<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Model;

use App\Entity\DocumentRow;
use App\Exception\InvalidDocumentRowVatException;
use App\Exception\UndefinedDocumentRow;
use Doctrine\Common\Collections\ArrayCollection;

class VatGroup
{
    /** @var */
    private $taxRate;

    /** @var array */
    private $rows = [];

    public function __construct($taxRate)
    {
        $this->taxRate = $taxRate;
        $this->rows = new ArrayCollection();
    }

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    public function formatTaxRate()
    {
        return $this->taxRate * 100;
    }

    public function addRow(DocumentRow $row)
    {
        if ($row->getTaxRateAmount() != $this->taxRate) {
            throw new InvalidDocumentRowVatException;
        }

        $this->rows->add($row);
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getTaxesTotal()
    {
        $total = 0;

        /** @var DocumentRow $row */
        foreach ($this->rows as $row) {
            $total += $row->getTaxes();
        }

        return $total;
    }

    public function getNetTotal()
    {
        $total = 0;

        /** @var DocumentRow $row */
        foreach ($this->rows as $row) {
            $total += $row->getNetCost();
        }

        return $total;
    }

    public function isEmpty()
    {
        return $this->rows->isEmpty();
    }

    public function removeRow(DocumentRow $row)
    {
        if (!$this->rows->contains($row)) {
            throw new UndefinedDocumentRow();
        }

        $this->rows->removeElement($row);
    }

}
