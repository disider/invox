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

use App\Entity\Product;
use App\Entity\User;
use App\Model\Module;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProductVoter extends BaseVoter
{
    const PRODUCT_DELETE = 'PRODUCT_DELETE';
    const PRODUCT_EDIT = 'PRODUCT_EDIT';
    const PRODUCT_SHOW_WAREHOUSE = 'PRODUCT_SHOW_WAREHOUSE';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Product && in_array(
                $attribute,
                [
                    self::PRODUCT_DELETE,
                    self::PRODUCT_EDIT,
                    self::PRODUCT_SHOW_WAREHOUSE,
                ]
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        switch ($attribute) {
            case self::PRODUCT_DELETE:
            case self::PRODUCT_EDIT:
                return ($user->ownsProduct($subject) && $this->isModuleEnabled(
                        $subject->getCompany(),
                        Module::PRODUCTS_MODULE
                    ));
            case self::PRODUCT_SHOW_WAREHOUSE:
                return ($user->ownsProduct($subject) && $this->isModuleEnabled(
                        $subject->getCompany(),
                        Module::WAREHOUSE_MODULE
                    ));
        }

        return false;
    }
}
