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

class Language
{
    const ITALIAN = 'it';
    const ENGLISH = 'en';

    public static function getLanguages()
    {
        return [
            self::ITALIAN,
            self::ENGLISH,
        ];
    }
}
