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
//use AppBundle\Entity\Company;
//
//class CompanyRepositoryTest extends BaseUserRepositoryTest
//{
//    /**
//     * @test
//     */
//    public function testFindOneById()
//    {
//        $company = $this->givenCompany('Acme');
//
//        $this->entityManager->clear();
//
//        $expected = $this->companyRepository->findOneById($company->getId());
//
//        $this->assertCompany($company, $expected);
//    }
//
//    /**
//     * @test
//     */
//    public function testFindAllPagination()
//    {
//        $this->givenCompanies(10, 'Acme');
//
//        $filters = array();
//        $companies = $this->companyRepository->findAll($filters, 0, 5);
//        $this->assertThat(count($companies), $this->equalTo(5));
//
//        $companies = $this->companyRepository->findAll($filters, 1, 5);
//        $this->assertThat(count($companies), $this->equalTo(5));
//
//        $companies = $this->companyRepository->findAll($filters, 2, 5);
//        $this->assertThat(count($companies), $this->equalTo(0));
//    }
//
//    /**
//     * @test
//     */
//    public function testCountAll()
//    {
//        $this->givenCompanies(10, 'Acme');
//
//        $filters = array();
//        $count = $this->companyRepository->countAll($filters);
//        $this->assertThat($count, $this->equalTo(10));
//    }
//
//    /**
//     * @test
//     */
//    public function testDelete()
//    {
//        $company = $this->givenCompany('Acme');
//
//        $this->companyRepository->delete($company);
//
//        $this->entityManager->clear();
//
//        $company = $this->companyRepository->findOneById($company->getId());
//        $this->assertNull($company);
//    }
//
//    private function assertCompany(Company $actual, Company $expected)
//    {
//        $this->assertField($expected, $actual, 'id');
//        $this->assertField($expected, $actual, 'name');
//        $this->assertField($expected, $actual, 'address');
//        $this->assertField($expected, $actual, 'vatNumber');
//    }
//}
