<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\TaxRate;
use AppBundle\Entity\User;
use AppBundle\Model\DocumentType;
use AppBundle\Model\VatGroup;

class DocumentTest extends \PhpUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);

        $issueDate = new \DateTime();
        $number = 123;
        $document = $this->givenDocument(DocumentType::QUOTE, $company, $number, $issueDate);

        $this->assertThat($document->getCompany(), $this->equalTo($company));
        $this->assertThat($document->getCompanyName(), $this->equalTo($company->getName()));
        $this->assertThat($document->getCompanyAddress(), $this->equalTo($company->getAddress()));
        $this->assertThat($document->getCompanyVatNumber(), $this->equalTo($company->getVatNumber()));
        $this->assertThat($document->getRef(), $this->equalTo($number));
        $this->assertThat($document->getIssuedAt(), $this->equalTo($issueDate));
        $this->assertThat($document->countRows(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testCreateEmpty()
    {
        $number = '001';
        $year = 2015;
        $document = Document::createEmpty(DocumentType::QUOTE, Document::INCOMING, $number, $year);

        $this->assertThat($document->getRef(), $this->equalTo($number));
        $this->assertThat($document->getIssuedAt(), $this->equalTo(new \DateTime()));
        $this->assertThat($document->countRows(), $this->equalTo(1));
    }

    /**
     * @test
     */
    public function testToString()
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);
        $customer = $this->givenCustomer($country, $company);
        $document = $this->givenDocument(DocumentType::QUOTE, $company, 123, 2015);

        $this->assertThat((string)$document, $this->equalTo('123/15'));
        $this->assertThat($document->formatRef(), $this->equalTo('123/15'));
        $this->assertThat($document->formatRef('-'), $this->equalTo('123-15'));
    }

    /**
     * @test
     * @expectedException \AppBundle\Exception\InvalidDocumentTypeException
     */
    public function throwWhenInvalidType()
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);
        $this->givenDocument('invalid', $company, 123, new \DateTime());
    }

    /**
     * @test
     */
    public function testAddRow()
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);
        $document = $this->givenDocument(DocumentType::QUOTE, $company);

        $row = $this->givenDocumentRow(0, 100, 1, 22);

        $document->addRow($row);

        $document->calculateTotals();

        $this->assertThat($document->countRows(), $this->equalTo(1));

        $this->assertThat($document->getRow(0), $this->equalTo($row));
        $this->assertThat($document->getNetTotal(), $this->equalTo(100));
        $this->assertThat($document->getTaxes(), $this->equalTo(22));
        $this->assertThat($document->getGrossTotal(), $this->equalTo(122));
    }

    /**
     * @test
     */
    public function testGroupRowsByVat()
    {
        $document = $this->givenFullDocument();

        $document->addRow($this->givenDocumentRow(0, 10, 2, 10));
        $document->addRow($this->givenDocumentRow(1, 200, 1, 22));
        $document->addRow($this->givenDocumentRow(2, 200, 10, 22));

        $collection = $document->getVatGroupCollection();
        $groups = $collection->getGroups();

        $this->assertVatGroup($groups[0], 10, 1, 2);
        $this->assertVatGroup($groups[1], 22, 2, 484);
    }

    /**
     * @test
     */
    public function testTotal()
    {
        $document = $this->givenFullDocument();

        $document->addRow($this->givenDocumentRow(0, 10.333, 1, 10, 1));
        $document->addRow($this->givenDocumentRow(1, 20.666, 2, 20, 2));
        $document->setDiscount(5);
        $document->setRounding(-0.1);

        $document->calculateTotals();

        $rows = $document->getRows();

        $this->assertThat($rows[0]->getNetCost(), $this->equalTo(9.33));
        $this->assertThat($rows[0]->getGrossCost(), $this->equalTo(10.27));
        $this->assertThat($rows[1]->getGrossCost(), $this->equalTo(44.80));
        $this->assertThat($document->getGrossTotal(), $this->equalTo(49.96));
    }

    /** @return Customer */
    private function givenCustomer(Country $country, Company $company)
    {
        return Customer::create($company, 'Customer', 'customer@example.com', '0123456789', '09876543210', $country, 'Rome', 'RM', '00100', 'Abbey Road', '');
    }

    /** @return Document */
    private function givenDocument($type, Company $company, $number = '001', $year = 2014, $issueDate = null, $direction = Document::NO_DIRECTION)
    {
        if (!$issueDate) {
            $issueDate = new \DateTime();
        }

        return Document::create($type, $direction, $company, $number, $year, $issueDate);
    }

    private function givenCompany(Country $country, User $owner)
    {
        return Company::create($country, $owner, 'Acme', '123, Street, City', '9876543210');
    }

    private function givenUser()
    {
        return User::create('user@example.com', '', '');
    }

    private function givenFullDocument($number = '001', $year = 2014)
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);
        $document = $this->givenDocument(DocumentType::QUOTE, $company, $number, $year);

        return $document;
    }

    private function assertVatGroup(VatGroup $group, $vat, $count, $taxableTotal)
    {
        $this->assertThat($group->getTaxRate(), $this->equalTo($vat));
        $this->assertThat(count($group->getRows()), $this->equalTo($count));
        $this->assertThat($group->getTaxesTotal(), $this->equalTo($taxableTotal));
    }

    protected function givenDocumentRow($position, $unitPrice, $quantity, $vat, $discount = 0)
    {
        $taxRate = TaxRate::create('TaxRate' . $vat, $vat);
        $row = DocumentRow::create(null, $position, 'Line' . $position, '', $unitPrice, $quantity, $taxRate, $discount);

        return $row;
    }

    private function givenCountry()
    {
        return Country::create('Italy');
    }
}
