<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Helper;

use AppBundle\Validator\Constraints as CustomAssert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class WeekdayHelper
{
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY = 'sunday';

    public static function daysValues()
    {
        return array(
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
            self::SUNDAY,
        );
    }

    public static function getDaysArray($weekdays)
    {
        $days = [];

        $values = self::daysValues();

        for ($i = 0; $i < count($values); ++$i) {
            if ($weekdays[$i] == '1') {
                $days[] = $values[$i];
            }
        }

        return $days;
    }
}