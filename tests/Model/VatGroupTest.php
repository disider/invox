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
use App\Model\VatGroup;
use Tests\App\EntityTest;

class VatGroupTest extends EntityTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $group = new VatGroup(20);

        $this->assertTrue($group->isEmpty());
        $this->assertThat($group->getTaxRate(), $this->equalTo(20));
        $this->assertThat($group->getNetTotal(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testAddRow()
    {
        $group = new VatGroup(20);

        $taxRate = TaxRate::create('TaxRate', 20);
        $row = DocumentRow::create(null, 0, 'Row', '', 10, 1, $taxRate, 0);

        $group->addRow($row);

        $this->assertFalse($group->isEmpty());
        $this->assertThat($group->getTaxesTotal(), $this->equalTo(2));
        $this->assertThat($group->getNetTotal(), $this->equalTo(10));
    }

    /**
     * @test
     */
    public function testRemoveRow()
    {
        $group = new VatGroup(20);
        $taxRate = TaxRate::create('TaxRate', 20);
        $row = DocumentRow::create(null, 0, 'Row', '', 10, 1, $taxRate, 0);

        $group->addRow($row);
        $group->removeRow($row);

        $this->assertTrue($group->isEmpty());
        $this->assertThat($group->getTaxesTotal(), $this->equalTo(0));
        $this->assertThat($group->getNetTotal(), $this->equalTo(0));
    }

    /**
     * @test
     * @expectedException \App\Exception\InvalidDocumentRowVatException
     */
    public function cannotAddRowWithDifferentVat()
    {
        $group = new VatGroup(20);
        $taxRate = TaxRate::create('TaxRate', 10);
        $row = DocumentRow::create(null, 0, 'Row', '', 10, 1, $taxRate, 0);

        $group->addRow($row);
    }

    /**
     * @test
     * @expectedException \App\Exception\UndefinedDocumentRow
     */
    public function cannotRemoveUndefinedRow()
    {
        $group = new VatGroup(20);
        $taxRate = TaxRate::create('TaxRate', 10);
        $row = DocumentRow::create(null, 0, 'Row', '', 10, 1, $taxRate, 0);

        $group->removeRow($row);
    }
}
