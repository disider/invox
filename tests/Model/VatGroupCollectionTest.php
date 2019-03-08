<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Model;

use App\Entity\DocumentRow;
use App\Entity\TaxRate;
use App\Model\VatGroupCollection;
use Tests\App\EntityTest;
use Doctrine\Common\Collections\ArrayCollection;

class VatGroupCollectionTest extends EntityTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $collection = new VatGroupCollection();

        $this->assertFalse($collection->hasGroups());
        $this->assertThat($collection->getTaxesTotal(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function addRow()
    {
        $collection = new VatGroupCollection();
        $row = $this->givenRow(10, 1, 20);

        $collection->addRow($row);

        $this->assertTrue($collection->hasGroups());
        $this->assertThat($collection->getTaxesTotal(), $this->equalTo(2));
    }

    /**
     * @test
     */
    public function removeRow()
    {
        $collection = new VatGroupCollection();
        $row = $this->givenRow(10, 1, 20);

        $collection->addRow($row);
        $collection->removeRow($row);

        $this->assertFalse($collection->hasGroups());
        $this->assertThat($collection->getTaxesTotal(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function sortRows()
    {
        $collection = new VatGroupCollection();

        $row1 = $this->givenRow(10, 1, 10);
        $row2 = $this->givenRow(10, 1, 20);

        $collection->addRow($row1);
        $collection->addRow($row2);

        /** @var ArrayCollection $groups */
        $groups = $collection->getGroups();
        $groups = $groups->toArray();

        $this->assertThat($groups[0]->getTaxRate(), $this->equalTo(10));
        $this->assertThat($groups[1]->getTaxRate(), $this->equalTo(20));
    }

    protected function givenRow($unitPrice, $quantity, $vat)
    {
        $taxRate = TaxRate::create('TaxRate', $vat);
        $row = DocumentRow::create(null, 0, 'Row', '', $unitPrice, $quantity, $taxRate, 0);
        return $row;
    }
}
