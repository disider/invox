<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Manager\CompanyManager;
use AppBundle\Entity\User;
use AppBundle\Model\DocumentType;
use AppBundle\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RoleVoter extends Voter
{
    const ACCOUNT_CREATE = 'ACCOUNT_CREATE';
    const CITY_CREATE = 'CITY_CREATE';
    const COMPANY_CREATE = 'COMPANY_CREATE';
    const COUNTRY_CREATE = 'COUNTRY_CREATE';
    const CUSTOMER_CREATE = 'CUSTOMER_CREATE';
    const DOCUMENT_CREATE = 'DOCUMENT_CREATE';
    const DOCUMENT_TEMPLATE_CREATE = 'DOCUMENT_TEMPLATE_CREATE';
    const FIRST_COMPANY_CREATE = 'FIRST_COMPANY_CREATE';
    const MEDIUM_CREATE = 'MEDIUM_CREATE';
    const PAGE_CREATE = 'PAGE_CREATE';
    const PARAGRAPH_TEMPLATE_CREATE = 'PARAGRAPH_TEMPLATE_CREATE';
    const PAYMENT_TYPE_CREATE = 'PAYMENT_TYPE_CREATE';
    const PETTY_CASH_NOTE_CREATE = 'PETTY_CASH_NOTE_CREATE';
    const PRODUCT_CREATE = 'PRODUCT_CREATE';
    const PRODUCT_SEARCH = 'PRODUCT_SEARCH';
    const PROVINCE_CREATE = 'PROVINCE_CREATE';
    const RECURRENCE_CREATE = 'RECURRENCE_CREATE';
    const SERVICE_CREATE = 'SERVICE_CREATE';
    const SERVICE_SEARCH = 'SERVICE_SEARCH';
    const TAX_RATE_CREATE = 'TAX_RATE_CREATE';
    const USER_CREATE = 'USER_CREATE';
    const WORKING_NOTE_CREATE = 'WORKING_NOTE_CREATE';
    const ZIP_CODE_CREATE = 'ZIP_CODE_CREATE';
    const LIST_ACCOUNTS = 'LIST_ACCOUNTS';
    const LIST_CITIES = 'LIST_CITIES';
    const LIST_COMPANIES = 'LIST_COMPANIES';
    const LIST_COUNTRIES = 'LIST_COUNTRIES';
    const LIST_CREDIT_NOTES = 'LIST_CREDIT_NOTES';
    const LIST_CUSTOMERS = 'LIST_CUSTOMERS';
    const LIST_DELIVERY_NOTES = 'LIST_DELIVERY_NOTES';
    const LIST_DOCUMENTS = 'LIST_DOCUMENTS';
    const LIST_INVITES = 'LIST_INVITES';
    const LIST_INVOICES = 'LIST_INVOICES';
    const LIST_MEDIA = 'LIST_MEDIA';
    const LIST_MODULES = 'LIST_MODULES';
    const LIST_ORDERS = 'LIST_ORDERS';
    const LIST_PARAGRAPH_TEMPLATES = 'LIST_PARAGRAPH_TEMPLATES';
    const LIST_PAYMENT_TYPES = 'LIST_PAYMENT_TYPES';
    const LIST_PETTY_CASH_NOTES = 'LIST_PETTY_CASH_NOTES';
    const LIST_PRODUCTS = 'LIST_PRODUCTS';
    const LIST_PROVINCES = 'LIST_PROVINCES';
    const LIST_QUOTES = 'LIST_QUOTES';
    const LIST_RECURRENCES = 'LIST_RECURRENCES';
    const LIST_RECEIPTS = 'LIST_RECEIPTS';
    const LIST_SERVICES = 'LIST_SERVICES';
    const LIST_TAX_RATES = 'LIST_TAX_RATES';
    const LIST_USERS = 'LIST_USERS';
    const LIST_WORKING_NOTES = 'LIST_WORKING_NOTES';
    const LIST_ZIP_CODES = 'LIST_ZIP_CODES';
    const SHOW_SETTINGS = 'SHOW_SETTINGS';

    /**
     * @var CompanyManager
     */
    private $companyManager;

    public function __construct(CompanyManager $companyManager)
    {
        $this->companyManager = $companyManager;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [
            self::ACCOUNT_CREATE,
            self::CITY_CREATE,
            self::COMPANY_CREATE,
            self::COUNTRY_CREATE,
            self::CUSTOMER_CREATE,
            self::DOCUMENT_CREATE,
            self::DOCUMENT_TEMPLATE_CREATE,
            self::FIRST_COMPANY_CREATE,
            self::MEDIUM_CREATE,
            self::PAGE_CREATE,
            self::PARAGRAPH_TEMPLATE_CREATE,
            self::PAYMENT_TYPE_CREATE,
            self::PETTY_CASH_NOTE_CREATE,
            self::PRODUCT_CREATE,
            self::PRODUCT_SEARCH,
            self::PROVINCE_CREATE,
            self::RECURRENCE_CREATE,
            self::SERVICE_CREATE,
            self::SERVICE_SEARCH,
            self::TAX_RATE_CREATE,
            self::USER_CREATE,
            self::WORKING_NOTE_CREATE,
            self::ZIP_CODE_CREATE,
            self::LIST_ACCOUNTS,
            self::LIST_COMPANIES,
            self::LIST_CREDIT_NOTES,
            self::LIST_CUSTOMERS,
            self::LIST_CITIES,
            self::LIST_COUNTRIES,
            self::LIST_DELIVERY_NOTES,
            self::LIST_DOCUMENTS,
            self::LIST_INVITES,
            self::LIST_INVOICES,
            self::LIST_MEDIA,
            self::LIST_MODULES,
            self::LIST_ORDERS,
            self::LIST_PARAGRAPH_TEMPLATES,
            self::LIST_PETTY_CASH_NOTES,
            self::LIST_PRODUCTS,
            self::LIST_PROVINCES,
            self::LIST_QUOTES,
            self::LIST_RECURRENCES,
            self::LIST_RECEIPTS,
            self::LIST_SERVICES,
            self::LIST_PAYMENT_TYPES,
            self::LIST_TAX_RATES,
            self::LIST_USERS,
            self::LIST_WORKING_NOTES,
            self::LIST_ZIP_CODES,
            self::SHOW_SETTINGS,
        ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!($token instanceof UsernamePasswordToken) && !($token instanceof RememberMeToken)) {
            return false;
        }

        $user = $token->getUser();

        switch ($attribute) {
            case self::CITY_CREATE:
            case self::COUNTRY_CREATE:
            case self::DOCUMENT_TEMPLATE_CREATE:
            case self::PAGE_CREATE:
            case self::PAYMENT_TYPE_CREATE:
            case self::PROVINCE_CREATE:
            case self::TAX_RATE_CREATE:
            case self::ZIP_CODE_CREATE:
            case self::LIST_CITIES:
            case self::LIST_COUNTRIES:
            case self::LIST_PAYMENT_TYPES:
            case self::LIST_PROVINCES:
            case self::LIST_TAX_RATES:
            case self::LIST_ZIP_CODES:
                return $user->isSuperadmin();
            case self::LIST_COMPANIES:
                return ($user->canManageMultipleCompanies() || $user->isAccountant() || $user->isSalesAgent());
            case self::LIST_ACCOUNTS:
                return ($this->canManageOrAccountCompany($user) && $this->isModuleEnabled(Module::ACCOUNTS_MODULE));
            case self::LIST_QUOTES:
                return $this->canManageCurrentCompany($user) && $this->canCompanyHandle(DocumentType::QUOTE);
            case self::LIST_ORDERS:
                return $this->canManageCurrentCompany($user) && $this->canCompanyHandle(DocumentType::ORDER);
            case self::LIST_DOCUMENTS:
                return ($this->canManageOrAccountCompany($user) || $user->isSalesAgentFor($this->getCurrentCompany()));
            case self::LIST_INVOICES:
                return $this->canManageOrAccountCompany($user) && $this->canCompanyHandle(DocumentType::INVOICE);
            case self::LIST_CREDIT_NOTES:
                return $this->canManageOrAccountCompany($user) && $this->canCompanyHandle(DocumentType::CREDIT_NOTE);
            case self::LIST_DELIVERY_NOTES:
                return $this->canManageOrAccountCompany($user) && $this->canCompanyHandle(DocumentType::DELIVERY_NOTE);
            case self::LIST_RECEIPTS:
                return $this->canManageOrAccountCompany($user) && $this->canCompanyHandle(DocumentType::RECEIPT);
            case self::LIST_PETTY_CASH_NOTES:
                return ($this->canManageOrAccountCompany($user) && $this->isModuleEnabled(Module::PETTY_CASH_NOTES_MODULE));
            case self::ACCOUNT_CREATE:
                return (($user->isSuperadmin() && $this->hasCurrentCompany()) || ($this->canManageCurrentCompany($user) && $this->isModuleEnabled(Module::ACCOUNTS_MODULE)));
            case self::CUSTOMER_CREATE:
            case self::DOCUMENT_CREATE:
            case self::LIST_CUSTOMERS:
            case self::MEDIUM_CREATE:
            case self::LIST_MEDIA:
                return (($user->isSuperadmin() && $this->hasCurrentCompany()) || $this->canManageCurrentCompany($user));
            case self::PETTY_CASH_NOTE_CREATE:
                return (($user->isSuperadmin() && $this->hasCurrentCompany()) || ($this->canManageCurrentCompany($user) && $this->isModuleEnabled(Module::PETTY_CASH_NOTES_MODULE)));
            case self::LIST_MODULES:
                return ($this->hasCurrentCompany() && ($user->isSuperadmin() || $user->ownsCompany($this->getCurrentCompany())));
            case self::LIST_INVITES:
                return ($user->isSuperadmin() || $user->hasReceivedInvites());
            case self::COMPANY_CREATE:
                return (!$user->hasOwnedCompanies() || $user->canManageMultipleCompanies());
            case self::FIRST_COMPANY_CREATE:
                return (!$user->isSuperadmin() && $user->hasRole(User::ROLE_OWNER) && !$user->hasOwnedCompanies());
            case self::PRODUCT_CREATE:
            case self::PRODUCT_SEARCH:
            case self::LIST_PRODUCTS:
                return ($this->canManageCurrentCompany($user) && $this->isModuleEnabled(Module::PRODUCTS_MODULE));
            case self::RECURRENCE_CREATE:
            case self::LIST_RECURRENCES:
                return ($this->canManageCurrentCompany($user) && $this->isModuleEnabled(Module::RECURRENCE_MODULE));
            case self::SERVICE_CREATE:
            case self::SERVICE_SEARCH:
            case self::LIST_SERVICES:
                return ($this->canManageCurrentCompany($user) && $this->isModuleEnabled(Module::SERVICES_MODULE));
            case self::USER_CREATE:
            case self::LIST_USERS:
                return $user->canManageMultipleCompanies();
            case self::LIST_PARAGRAPH_TEMPLATES:
            case self::LIST_WORKING_NOTES:
            case self::PARAGRAPH_TEMPLATE_CREATE:
            case self::WORKING_NOTE_CREATE:
                return ($this->canManageCurrentCompany($user) || $user->isSalesAgent()) && $this->isModuleEnabled(Module::WORKING_NOTES_MODULE);
            case self::SHOW_SETTINGS:
                return ($this->canManageCurrentCompany($user) || $user->isSalesAgentFor($this->getCurrentCompany()));
        }

        return false;
    }

    private function hasCurrentCompany()
    {
        return $this->companyManager->hasCurrent();
    }

    private function getCurrentCompany()
    {
        return $this->companyManager->getCurrent();
    }

    private function canManageCurrentCompany(User $user)
    {
        return $this->hasCurrentCompany() && $user->canManageCompany($this->getCurrentCompany());
    }

    private function canAccountantCurrentCompany(User $user)
    {
        return $user->isAccountant() && $this->hasCurrentCompany();
    }

    private function isModuleEnabled($module)
    {
        return $this->getCurrentCompany()->hasModule(new Module($module));
    }

    protected function canManageOrAccountCompany(User $user)
    {
        return (($user->isSuperadmin() && $this->hasCurrentCompany())
            || $this->canManageCurrentCompany($user)
            || $this->canAccountantCurrentCompany($user));
    }

    private function canCompanyHandle($documentType)
    {
        return $this->getCurrentCompany()->hasDocumentType($documentType);
    }
}
