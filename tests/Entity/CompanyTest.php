<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Tests\App\Entity;

use App\Entity\Company;
use App\Entity\Country;
use App\Entity\User;
use App\Model\DocumentType;
use Tests\App\EntityTest;

class CompanyTest extends EntityTest
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $name = 'Name';
        $address = 'Address';
        $vatNumber = 'VAT';

        $country = Country::create('Italy');
        $owner = User::create('user@example.com', '', '');

        /** @var Company $company */
        $company = Company::create($country, $owner, $name, $address, $vatNumber);

        $this->assertThat($company->getName(), $this->equalTo($name));
        $this->assertThat($company->getAddress(), $this->equalTo($address));
        $this->assertThat($company->getVatNumber(), $this->equalTo($vatNumber));
        $this->assertThat($company->getDocumentTypes(), $this->equalTo([]));
    }

    /**
     * @test
     */
    public function testDocumentTypes()
    {
        $country = Country::create('Italy');
        $owner = User::create('user@example.com', '', '');

        $company = Company::create($country, $owner, 'Acme', '', '');
        $company->addDocumentType(DocumentType::INVOICE);

        $this->assertThat($company->getDocumentTypes(), $this->equalTo([
            DocumentType::INVOICE,
        ]));
        $this->assertTrue($company->hasDocumentType(DocumentType::INVOICE));
        $this->assertFalse($company->hasDocumentType(DocumentType::RECEIPT));
    }

    /**
     * @test
     */
    public function addSameDocumentTypeTwice()
    {
        $country = Country::create('Italy');
        $owner = User::create('user@example.com', '', '');

        $company = Company::create($country, $owner, 'Acme', '', '');
        $company->addDocumentType(DocumentType::INVOICE);
        $company->addDocumentType(DocumentType::INVOICE);

        $this->assertThat($company->getDocumentTypes(), $this->equalTo([
            DocumentType::INVOICE,
        ]));
    }

    /**
     * @test
     * @expectedException \App\Exception\InvalidDocumentTypeException
     */
    public function cannotAddInvalidDocumentType()
    {
        $country = Country::create('Italy');
        $owner = User::create('user@example.com', '', '');

        $company = Company::create($country, $owner, 'Acme', '', '');
        $company->addDocumentType('invalid');
    }
}
