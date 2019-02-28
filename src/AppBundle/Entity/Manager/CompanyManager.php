<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity\Manager;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CompanyManager
{
    const CURRENT_COMPANY = 'current_company';

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Session
     */
    private $session;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var Company|null
     */
    private $currentCompany;

    public function __construct(CompanyRepository $companyRepository, TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->companyRepository = $companyRepository;
    }

    public function getCurrent()
    {
        $user = $this->getUser();

        if (!($user->isAccountant() || $user->isManagingSingleCompany() || $user->canManageMultipleCompanies() || $user->isSalesAgent())) {
            throw new \LogicException('Cannot get current company if user is not managing companies');
        }

        if ($user->isManagingSingleCompany()) {
            return $user->getManagedCompanies()->get(0);
        }

        if (!$this->hasCurrent()) {
            throw new \LogicException('Cannot get current company if user is managing multiple companies and no company is selected');
        }

        return $this->currentCompany;
    }

    public function hasCurrent()
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($user->isManagingSingleCompany()) {
            return true;
        }

        if (!$this->session->has(self::CURRENT_COMPANY)) {
            return false;
        }

        $companyId = $this->session->get(self::CURRENT_COMPANY);

        $this->currentCompany = $this->companyRepository->findOneById($companyId);

        if (!$this->currentCompany) {
            $this->session->remove(self::CURRENT_COMPANY);
            return false;
        }

        return true;
    }

    public function setCurrent(Company $company = null)
    {
        if ($company) {
            $this->session->set(self::CURRENT_COMPANY, $company->getId());
        } else {
            $this->session->remove(self::CURRENT_COMPANY);
        }
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}