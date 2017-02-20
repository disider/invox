<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Model;

class Module
{
    const ACCOUNTS_MODULE = 'accounts';
    const PETTY_CASH_NOTES_MODULE = 'petty_cash_notes';
    const PRODUCTS_MODULE = 'products';
    const SERVICES_MODULE = 'services';
    const WAREHOUSE_MODULE = 'warehouse';
    const WORKING_NOTES_MODULE = 'working_notes';
    const RECURRENCE_MODULE = 'recurrences';

    /**
     * @var string
     */
    private $name;

    public static function getAll()
    {
        return [
            new Module(self::ACCOUNTS_MODULE),
            new Module(self::PETTY_CASH_NOTES_MODULE),
            new Module(self::PRODUCTS_MODULE),
            new Module(self::SERVICES_MODULE),
            new Module(self::WAREHOUSE_MODULE),
            new Module(self::WORKING_NOTES_MODULE),
            new Module(self::RECURRENCE_MODULE),
        ];
    }

    public static function getTypes()
    {
        return [
            self::ACCOUNTS_MODULE,
            self::PETTY_CASH_NOTES_MODULE,
            self::PRODUCTS_MODULE,
            self::SERVICES_MODULE,
            self::WAREHOUSE_MODULE,
            self::WORKING_NOTES_MODULE,
            self::RECURRENCE_MODULE,
        ];
    }

    public function __construct($name)
    {
        $name = str_replace('-', '_', $name);

        $this->name = $name;
    }

    public function __toString()
    {
        return 'module.' . $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return str_replace('_', '-', $this->name);
    }
}
