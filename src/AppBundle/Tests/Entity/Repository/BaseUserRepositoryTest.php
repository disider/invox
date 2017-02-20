<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

//
//namespace AppBundle\Tests\Entity\Repository;
//
//use AppBundle\Entity\Company;
//use AppBundle\Entity\Manager\UserManager;
//use AppBundle\Entity\Repository\CompanyRepository;
//use AppBundle\Entity\Repository\UserRepository;
//use AppBundle\Entity\User;
//use AppBundle\Tests\RepositoryTestCase;
//
//abstract class BaseUserRepositoryTest extends RepositoryTestCase
//{
//    /** @var CompanyRepository */
//    protected $companyRepository;
//
//    /** @var UserRepository */
//    protected $userRepository;
//
//    /**
//     * @before
//     */
//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->companyRepository = $this->entityManager->getRepository('AppBundle:Company');
//        $this->userRepository = $this->entityManager->getRepository('AppBundle:User');
//    }
//
//    protected function givenCompanies($number, $companyName)
//    {
//        for ($i = 0; $i < $number; ++$i) {
//            $this->givenCompany($companyName.' '.$i);
//        }
//    }
//
//    protected function givenCompany($companyName)
//    {
//        $company = Company::create($companyName, 'Company address', '027345982');
//
//        return $this->companyRepository->save($company);
//    }
//
//    protected function givenUsers($number, $companyName = null, $offset = 0)
//    {
//        for ($i = 0; $i < $number; ++$i) {
//            $this->givenUser(($i + $offset).'@example.com', $companyName);
//        }
//    }
//
//    protected function givenUser($email = 'adam@example.com', $companyName = null)
//    {
//        $user = $this->buildUser($email, true, $companyName);
//
//        return $this->userRepository->save($user);
//    }
//
//    protected function givenInactiveUser($email = 'inactive@example.com', $companyName = null)
//    {
//        $user = $this->buildUser($email, false, $companyName);
//
//        return $this->userRepository->save($user);
//    }
//
//    protected function givenSuperadmin($email)
//    {
//        $user = $this->buildUser($email, true);
//        $user->addRole(User::ROLE_SUPER_ADMIN);
//
//        return $this->userRepository->save($user);
//    }
//
//    protected function givenAdmin($email, $companyName = null)
//    {
//        $user = $this->buildUser($email, true, $companyName);
//
//        $user->addRole(User::ROLE_ADMIN);
//
//        return $this->userRepository->save($user);
//    }
//
//    protected function buildUser($email, $enabled = true, $companyName = null)
//    {
//        $user = User::create($email, '', '');
//        $user->setUsername($email);
//        $user->setEnabled($enabled);
//
//        /** @var UserManager $userManager */
//        $userManager = $this->getService('user_manager');
//
//        $user = $userManager->updateUser($user);
//
//        if ($companyName != null) {
//            $company = $this->companyRepository->findOneByName($companyName);
//            $user->setCompany($company);
//        }
//
//        return $user;
//    }
//
//    protected function assertField($expected, $document, $field)
//    {
//        $method = 'get'.ucfirst($field);
//        $this->assertNotNull($expected->$method());
//        $this->assertThat($expected->$method(), $this->equalTo($document->$method()));
//    }
//}
