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

use AppBundle\Entity\Company;
use AppBundle\Entity\Country;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\InvoicePerNote;
use AppBundle\Entity\PettyCashNote;
use AppBundle\Entity\TaxRate;
use AppBundle\Entity\User;
use AppBundle\Model\DocumentType;
use AppBundle\Model\VatGroup;

class PettyCashNoteTest extends \PhpUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function printCustomers()
    {
        $owner = $this->givenUser();
        $country = $this->givenCountry();
        $company = $this->givenCompany($country, $owner);

        $issueDate = new \DateTime();
        $invoice = $this->givenInvoice($company, 123, $issueDate);
        $invoice->setCustomerName('Customer1');

        /** @var PettyCashNote $pettyCashNote */
        $pettyCashNote = PettyCashNote::create($company, 456, 100, new \DateTime());
        $invoicePerNote = InvoicePerNote::create($invoice, $pettyCashNote, 100);
        $pettyCashNote->addInvoice($invoicePerNote);

        $this->assertThat($pettyCashNote->getCustomers()->count(), $this->equalTo(1));
    }

    /** @return Document */
    private function givenInvoice(Company $company, $number = '001', $year = 2014, $issueDate = null)
    {
        if (!$issueDate) {
            $issueDate = new \DateTime();
        }

        return Document::create(DocumentType::INVOICE, Document::INCOMING, $company, $number, $year, $issueDate);
    }

    private function givenCompany(Country $country, User $owner)
    {
        return Company::create($country, $owner, 'Acme', '123, Street, City', '9876543210');
    }

    private function givenUser()
    {
        return User::create('user@example.com', '', '');
    }

    private function givenCountry()
    {
        return Country::create('Italy');
    }
}
