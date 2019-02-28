<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

//
//namespace Tests\AppBundle\Entity\Repository;
//
//use AppBundle\Entity\Customer;
//use AppBundle\Entity\Document;
//use AppBundle\Entity\DocumentRow;
//use AppBundle\Model\DocumentType;
//use AppBundle\Repository\CustomerRepository;
//use AppBundle\Repository\DocumentRepository;
//use AppBundle\Entity\User;
//use AppBundle\Helper\ProtocolGenerator;
//
//class DocumentRepositoryTest extends BaseUserRepositoryTest
//{
//    /** @var CustomerRepository */
//    private $customerRepository;
//
//    /** @var DocumentRepository */
//    private $documentRepository;
//
//    /**
//     * @before
//     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->customerRepository = $this->entityManager->getRepository('AppBundle:Customer');
//        $this->documentRepository = $this->entityManager->getRepository('AppBundle:Document');
//    }
//
//    /**
//     * @test
//     */
//    public function testFindOneById()
//    {
//        $company = $this->givenCompany('Acme');
//        $user = $this->givenUser('user@example.com', 'Acme');
//        $customer = $this->givenCustomer();
//        $document = $this->givenDocument($user, $customer, DocumentType::INVOICE, 2);
//
//        $this->entityManager->clear();
//
//        $expected = $this->documentRepository->findOneById($document->getId());
//
//        $this->assertDocument($document, $expected, 2);
//    }
//
//    /**
//     * @test
//     */
//    public function testFindAllPagination()
//    {
//        $user = $this->givenUser();
//        $customer = $this->givenCustomer();
//        $this->givenDocuments(10, $user, $customer, DocumentType::QUOTE);
//
//        $filters = array();
//        $companies = $this->documentRepository->findAll($filters, 0, 5);
//        $this->assertThat(count($companies), $this->equalTo(5));
//
//        $companies = $this->documentRepository->findAll($filters, 1, 5);
//        $this->assertThat(count($companies), $this->equalTo(5));
//
//        $companies = $this->documentRepository->findAll($filters, 2, 5);
//        $this->assertThat(count($companies), $this->equalTo(0));
//    }
//
//    /**
//     * @test
//     */
//    public function testCountAll()
//    {
//        $user = $this->givenUser();
//        $customer = $this->givenCustomer();
//        $this->givenDocuments(10, $user, $customer, DocumentType::QUOTE);
//
//        $filters = array();
//        $count = $this->documentRepository->countAll($filters);
//        $this->assertThat($count, $this->equalTo(10));
//    }
//
//    private function assertDocument(Document $actual, Document $expected, $rowsCount)
//    {
//        $this->assertField($actual, $expected, 'id');
//        $this->assertField($actual, $expected, 'number');
//        $this->assertField($actual, $expected, 'year');
//        $this->assertField($actual, $expected, 'companyId');
//        $this->assertField($actual, $expected, 'companyName');
//        $this->assertField($actual, $expected, 'companyAddress');
//        $this->assertField($actual, $expected, 'companyVatNumber');
//        $this->assertField($actual, $expected, 'customerId');
//        $this->assertField($actual, $expected, 'customerName');
//        $this->assertField($actual, $expected, 'customerAddress');
//        $this->assertField($actual, $expected, 'customerVatNumber');
//
//        $this->assertThat($actual->countRows(), $this->equalTo($rowsCount));
//
//        for ($i = 0; $i != $expected->countRows(); ++$i) {
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'position');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'title');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'description');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'unitPrice');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'quantity');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'vat');
//            $this->assertField($actual->getRow($i), $expected->getRow($i), 'discount');
//        }
//    }
//
//    protected function givenDocuments($number, User $user, Customer $customer, $type)
//    {
//        for ($i = 0; $i < $number; ++$i) {
//            $this->givenDocument($user, $customer, $type, 0);
//        }
//    }
//
//    protected function givenDocument(User $user, Customer $customer, $type, $rowsCount)
//    {
//        $generator = new ProtocolGenerator();
//        $document = Document::create(null,
//            $type,
//            $user->getCompanyId(),
//            $user->getCompanyName(),
//            $user->getCompanyAddress(),
//            $user->getCompanyVatNumber(),
//            $customer->getId(),
//            $customer->getName(),
//            $customer->getAddress(),
//            $customer->getVatNumber(),
//            $generator->generate(),
//            2014,
//            new \DateTime());
//
//        for ($i = 0; $i != $rowsCount; ++$i) {
//            $document->addRow($this->givenRow($i));
//        }
//
//        return $this->documentRepository->save($document);
//    }
//
//    protected function givenCustomer($name = 'Customer', $email = 'customer@example.com')
//    {
//        $customer = Customer::create(null, $name, $email, 'Abbey Road, 1234, London', '0123456789');
//
//        return $this->customerRepository->save($customer);
//    }
//
//    private function givenRow($i)
//    {
//        return DocumentRow::create(null, $i, 'Product '.$i, '', 100, 1, 22, 5);
//    }
//}
