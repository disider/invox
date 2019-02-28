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
//use AppBundle\Repository\CustomerRepository;
//
//class CustomerRepositoryTest extends BaseUserRepositoryTest
//{
//    /** @var CustomerRepository */
//    private $customerRepository;
//
//    /**
//     * @before
//     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->customerRepository = $this->entityManager->getRepository('AppBundle:Customer');
//    }
//
//    /**
//     * @test
//     */
//    public function testFindOneById()
//    {
//        $customer = $this->givenCustomer();
//
//        $this->entityManager->clear();
//
//        $expected = $this->customerRepository->findOneById($customer->getId());
//
//        $this->assertCustomer($customer, $expected);
//    }
//
//    /**
//     * @test
//     */
//    public function testFindAllPagination()
//    {
//        $this->givenCustomers(10);
//
//        $filters = array();
//        $customers = $this->customerRepository->findAll($filters, 0, 5);
//        $this->assertThat(count($customers), $this->equalTo(5));
//
//        $customers = $this->customerRepository->findAll($filters, 1, 5);
//        $this->assertThat(count($customers), $this->equalTo(5));
//
//        $customers = $this->customerRepository->findAll($filters, 2, 5);
//        $this->assertThat(count($customers), $this->equalTo(0));
//    }
//
//    /**
//     * @test
//     */
//    public function testCountAll()
//    {
//        $this->givenCustomers(10);
//
//        $filters = array();
//        $count = $this->customerRepository->countAll($filters);
//        $this->assertThat($count, $this->equalTo(10));
//    }
//
//    protected function givenCustomers($number)
//    {
//        for ($i = 0; $i < $number; ++$i) {
//            $this->givenCustomer('Customer '.$i);
//        }
//    }
//
//    protected function givenCustomer($name = 'Customer')
//    {
//        $customer = Customer::create(null, $name, sprintf('customer%d@example.com', $name), 'Abbey Road, 1234, London', '0123456789');
//
//        return $this->customerRepository->save($customer);
//    }
//
//    private function assertCustomer(Customer $actual, Customer $expected)
//    {
//        $this->assertField($expected, $actual, 'id');
//        $this->assertField($expected, $actual, 'name');
//        $this->assertField($expected, $actual, 'email');
//        $this->assertField($expected, $actual, 'address');
//        $this->assertField($expected, $actual, 'vatNumber');
//    }
//}
