<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Model;

class DocumentType
{
    const QUOTE = 'quote';
    const ORDER = 'order';
    const INVOICE = 'invoice';
    const CREDIT_NOTE = 'credit_note';
    const RECEIPT = 'receipt';
    const DELIVERY_NOTE = 'delivery_note';

    public static function getAll()
    {
        return [
            self::QUOTE,
            self::ORDER,
            self::INVOICE,
            self::CREDIT_NOTE,
            self::RECEIPT,
            self::DELIVERY_NOTE,
        ];
    }
}
