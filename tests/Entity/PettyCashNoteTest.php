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
use App\Entity\Document;
use App\Entity\InvoicePerNote;
use App\Entity\PettyCashNote;
use App\Entity\User;
use App\Model\DocumentType;
use PHPUnit\Framework\TestCase;

class PettyCashNoteTest extends TestCase
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
