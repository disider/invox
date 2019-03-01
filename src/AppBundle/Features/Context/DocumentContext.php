<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Features\Context;

use AppBundle\Entity\Company;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentAttachment;
use AppBundle\Entity\DocumentRow;
use AppBundle\Entity\Recurrence;
use AppBundle\Model\DocumentType;
use Behat\Gherkin\Node\TableNode;

class DocumentContext extends BaseMinkContext
{
    /**
     * @Given /^there is a quote:$/
     * @Given /^there are quotes:$/
     */
    public function thereAreQuotes(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::QUOTE);
    }

    /**
     * @Given /^there is an order:$/
     * @Given /^there are orders:$/
     */
    public function thereAreOrders(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::ORDER);
    }

    /**
     * @Given /^there is an invoice:$/
     * @Given /^there are invoices:$/
     */
    public function thereAreInvoices(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::INVOICE, Document::OUTGOING);
    }

    /**
     * @Given /^there is a receipt:$/
     * @Given /^there are receipts:$/
     */
    public function thereAreReceipts(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::RECEIPT, Document::OUTGOING);
    }

    /**
     * @Given /^there is a delivery note:$/
     * @Given /^there are delivery notes:$/
     */
    public function thereAreDeliveryNotes(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::DELIVERY_NOTE);
    }

    /**
     * @Given /^there are credit notes:$/
     * @Given /^there is a credit note:$/
     */
    public function thereIsACreditNote(TableNode $table)
    {
        $this->buildEntities($table, DocumentType::CREDIT_NOTE);
    }

    /**
     * @Given /^there is a document row:$/
     * @Given /^there are document rows:$/
     */
    public function thereAreRows(TableNode $table)
    {
        $this->buildRows($table);
    }

    /**
     * @Given /^there is a document attachment:$/
     */
    public function thereIsADocumentAttachment(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var Document $document */
            $document = $this->getDocumentRepository()->findOneByRef($values['document']);

            $this->buildAttachment(
                $document,
                $values['fileName'],
                $values['fileUrl'],
                DocumentAttachment::class
            );

            $this->getDocumentRepository()->save($document);
        }
    }

    /**
     * @Then /^I should see (\d+) documents$/
     * @Then /^I should see (\d+) document$/
     */
    public function iSeeDocuments($number)
    {
        $this->assertNumElements($number, '.document');
    }

    private function buildEntities(TableNode $table, $type, $defaultDirection = Document::NO_DIRECTION)
    {
        foreach ($table->getHash() as $key => $values) {
            $entity = $this->buildEntity(
                $type,
                $this->getValue($values, 'direction', $defaultDirection),
                $values['company'],
                $this->getValue($values, 'customer'),
                $this->getValue($values, 'customerName'),
                $this->getValue($values, 'customerCountry'),
                $values['ref'],
                $this->getValue($values, 'title'),
                $this->getValue($values, 'year', date('Y')),
                $this->getValue($values, 'language', 'en'),
                $this->getValue($values, 'linkedOrder'),
                $this->getValue($values, 'linkedInvoice'),
                $this->getDateValue($values, 'issuedAt'),
                $this->getValidUntil($type, $values),
                $this->getValue($values, 'status'),
                $this->getValue($values, 'costCenters'),
                $this->getValue($values, 'recurrence')
            );

            $this->getDocumentRepository()->save($entity);
        }
    }

    private function buildEntity($type, $direction, $companyName, $customerEmail, $customerName, $customerCountryName, $number, $title, $year, $language, $linkedOrderRef, $linkedInvoiceRef, $issuedAt, $validUntil, $status, $costCenters, $recurrence)
    {
        /** @var Company $company */
        $company = $this->getCompanyRepository()->findOneByName($companyName);

        $document = Document::create(
            $type,
            $direction,
            $company,
            $number,
            $year,
            $issuedAt,
            $language);

        $document->setTitle($title);
        $document->setDocumentTemplate($company->getFirstDocumentTemplate());

        if ($customerEmail) {
            /** @var Customer $customer */
            $customer = $this->getCustomerRepository()->findOneByEmail($customerEmail);
            $document->setLinkedCustomer($customer);
            $document->copyCustomerDetails();
        } else {
            $country = $this->getCountry($customerCountryName);

            $document->setCustomerName($customerName);
            $document->setCustomerCountry($country);
        }

        if ($validUntil) {
            $document->setValidUntil($validUntil);
        }

        if ($linkedOrderRef) {
            $entity = $this->getDocumentRepository()->findOneByRef($linkedOrderRef);
            $document->setLinkedOrder($entity);
        }

        if ($linkedInvoiceRef) {
            $entity = $this->getDocumentRepository()->findOneByRef($linkedInvoiceRef);
            $document->setLinkedInvoice($entity);
        }

        if ($recurrence) {
            /** @var Recurrence $recurrence */
            $recurrence = $this->getRecurrenceRepository()->findOneByContent($recurrence);
            $document->setRecurrence($recurrence);
        }

        if ($status) {
            $document->setStatus($status);
        }

        if (!empty($costCenters)) {
            $document->setCostCenters($costCenters);
        }

        return $document;
    }

    private function buildRows(TableNode $table)
    {
        foreach ($table->getHash() as $key => $values) {
            /** @var Document $document */
            $document = $this->getDocumentRepository()->findOneByRef($values['document']);

            $row = $this->buildRow(
                $key,
                $values['title'],
                $values['unitPrice'],
                $this->getIntValue($values, 'quantity', 1),
                $this->getFloatValue($values, 'taxRate', 0),
                $this->getValue($values, 'discount', 0)
            );

            if (isset($values['product'])) {
                $product = $this->getProductRepository()->findOneByCode($values['product']);
                $row->setLinkedProduct($product);
            }

            if (isset($values['service'])) {
                $service = $this->getServiceRepository()->findOneByCode($values['service']);
                $row->setLinkedService($service);
            }

            $document->addRow($row);
            $document->calculateTotals();

            $this->getDocumentRepository()->save($document);
        }
    }

    private function buildRow($position, $title, $unitPrice, $quantity, $taxRateAmount, $discount)
    {
        $taxRate = $this->getTaxRateRepository()->findOneByAmount($taxRateAmount);
        if (!$taxRate) {
            throw new \InvalidArgumentException('No tax rate found with amount: ' . $taxRateAmount);
        }

        return DocumentRow::create(null, $position, $title, '', $unitPrice, $quantity, $taxRate, $discount);
    }

    private function getValidUntil($type, $values)
    {
        if ($type == DocumentType::INVOICE) {
            return $this->getDateValue($values, 'validUntil', $this->getValue($values, 'issuedAt'));
        }

        if (isset($values['validUntil'])) {
            return $this->getDateValue($values, 'validUntil');
        }

        return null;
    }

}
