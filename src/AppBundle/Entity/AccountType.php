<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

class AccountType
{
    const CASH = 'cash';
    const BANK = 'bank';
    const CREDIT_CARD = 'credit_card';
    const DEBIT_CARD = 'debit_card';

    public static function getTypes()
    {
        return [
            self::CASH,
            self::BANK,
            self::CREDIT_CARD,
            self::DEBIT_CARD,
        ];
    }
}
