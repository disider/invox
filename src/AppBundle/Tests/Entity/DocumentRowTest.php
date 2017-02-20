<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\TaxRate;

class DocumentRowTest extends \PhpUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $row = $this->givenDocumentRow(0, 10, 1, 22, 0);

        $this->assertThat($row->getPosition(), $this->equalTo(0));
        $this->assertThat($row->getTitle(), $this->equalTo('Line0'));
        $this->assertThat($row->getDescription(), $this->equalTo('Line0 Description'));
        $this->assertThat($row->getNetCost(), $this->equalTo(10));
        $this->assertThat($row->getQuantity(), $this->equalTo(1));
        $this->assertThat($row->getDiscount(), $this->equalTo(0));
        $this->assertThat($row->getTaxRate()->getAmount(), $this->equalTo(22));
        $this->assertThat($row->getTaxes(), $this->equalTo(2.2));
        $this->assertThat($row->getGrossCost(), $this->equalTo(12.2));
    }

    /**
     * @test
     */
    public function testDecimals()
    {
        $row = $this->givenDocumentRow(0, 10.333, 1, 10, 1);
        $row->calculateTotals();

        $this->assertThat($row->getNetCost(), $this->equalTo(9.33));
        $this->assertThat($row->getTaxes(), $this->equalTo(0.93));
        $this->assertThat($row->getGrossCost(), $this->equalTo(10.27));
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\NonPositiveAmountException
     */
    public function whenAmountIsNotPositive_thenThrow()
    {
        $this->givenDocumentRow(0, -10, 1, 22, 0);
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\NonPositiveQuantityException
     */
    public function whenQuantityIsNotPositive_thenThrow()
    {
        $this->givenDocumentRow(0, 10, -1, 22, 0);
    }

    /**
     * @test
     */
    public function whenQuantityIsTwo_thenAmountIsDouble()
    {
        $row = $this->givenDocumentRow(0, 10, 2, 22, 0);

        $this->assertThat($row->getNetCost(), $this->equalTo(20));
        $this->assertThat($row->getQuantity(), $this->equalTo(2));
    }

    /**
     * @test
     */
    public function whenDiscounted_thenAmountIsDiscountedByFixedAmount()
    {
        $row = $this->givenDocumentRow(0, 10, 1, 0, 1, false);

        $this->assertThat($row->getDiscount(), $this->equalTo(1));
        $this->assertFalse($row->isDiscountPercent());
        $this->assertThat($row->getNetCost(), $this->equalTo(9));
        $this->assertThat($row->getGrossCost(), $this->equalTo(9));
    }

    /**
     * @test
     */
    public function whenDiscounted_thenAmountIsDiscountedByPercent()
    {
        $row = $this->givenDocumentRow(0, 10, 1, 0, 1, true);

        $this->assertThat($row->getDiscount(), $this->equalTo(1));
        $this->assertTrue($row->isDiscountPercent());
        $this->assertThat($row->getNetCost(), $this->equalTo(9.90));
        $this->assertThat($row->getGrossCost(), $this->equalTo(9.90));
    }

    protected function givenDocumentRow($position, $unitPrice, $quantity, $vat, $discount = 0, $discountPercent = false)
    {
        $taxRate = TaxRate::create('TaxRate' . $vat, $vat);
        $row = DocumentRow::create(null, $position, 'Line' . $position, 'Line' . $position . ' Description', $unitPrice, $quantity, $taxRate, $discount, $discountPercent);
        return $row;
    }
}
