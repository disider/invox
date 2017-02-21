<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

class BasePaymentType
{
    /** @var integer */
    protected $id;

    /** @var integer */
    protected $days;

    /** @var boolean */
    protected $endOfMonth;

    /** @var integer */
    protected $position;

    public function getId()
    {
        return $this->id;
    }

    public function setDays($days)
    {
        $this->days = $days;
    }

    public function getDays()
    {
        return $this->days;
    }

    public function setEndOfMonth($endOfMonth)
    {
        $this->endOfMonth = $endOfMonth;

        return $this;
    }

    public function getEndOfMonth()
    {
        return $this->endOfMonth;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setName($name, $locale = 'en')
    {
        $this->translate($locale)->setName($name);
    }

}
