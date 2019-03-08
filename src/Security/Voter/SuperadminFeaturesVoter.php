<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Security\Voter;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\DocumentTemplate;
use App\Entity\Page;
use App\Entity\PaymentType;
use App\Entity\Province;
use App\Entity\TaxRate;
use App\Entity\User;
use App\Entity\ZipCode;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SuperadminFeaturesVoter extends Voter
{
    const CITY_DELETE = 'CITY_DELETE';
    const CITY_EDIT = 'CITY_EDIT';
    const COUNTRY_DELETE = 'COUNTRY_DELETE';
    const COUNTRY_EDIT = 'COUNTRY_EDIT';
    const DOCUMENT_TEMPLATE_DELETE = 'DOCUMENT_TEMPLATE_DELETE';
    const DOCUMENT_TEMPLATE_EDIT = 'DOCUMENT_TEMPLATE_EDIT';
    const PAGE_DELETE = 'PAGE_DELETE';
    const PAGE_EDIT = 'PAGE_EDIT';
    const PAYMENT_TYPE_DELETE = 'PAYMENT_TYPE_DELETE';
    const PAYMENT_TYPE_EDIT = 'PAYMENT_TYPE_EDIT';
    const PROVINCE_DELETE = 'PROVINCE_DELETE';
    const PROVINCE_EDIT = 'PROVINCE_EDIT';
    const TAX_RATE_DELETE = 'TAX_RATE_DELETE';
    const TAX_RATE_EDIT = 'TAX_RATE_EDIT';
    const ZIP_CODE_DELETE = 'ZIP_CODE_DELETE';
    const ZIP_CODE_EDIT = 'ZIP_CODE_EDIT';

    protected function supports($attribute, $subject)
    {
        return ($this->isSubjectSupported($subject)) && in_array($attribute, [
                self::CITY_DELETE,
                self::CITY_EDIT,
                self::COUNTRY_DELETE,
                self::COUNTRY_EDIT,
                self::DOCUMENT_TEMPLATE_DELETE,
                self::DOCUMENT_TEMPLATE_EDIT,
                self::PAGE_DELETE,
                self::PAGE_EDIT,
                self::PAYMENT_TYPE_DELETE,
                self::PAYMENT_TYPE_EDIT,
                self::PROVINCE_DELETE,
                self::PROVINCE_EDIT,
                self::TAX_RATE_DELETE,
                self::TAX_RATE_EDIT,
                self::ZIP_CODE_DELETE,
                self::ZIP_CODE_EDIT,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!($user instanceof User))
            return false;

        if ($user->isSuperadmin())
            return true;

        return false;
    }

    private function isSubjectSupported($subject)
    {
        return $subject instanceof City
            || $subject instanceof Country
            || $subject instanceof DocumentTemplate
            || $subject instanceof Page
            || $subject instanceof PaymentType
            || $subject instanceof Province
            || $subject instanceof TaxRate
            || $subject instanceof ZipCode;
    }
}
