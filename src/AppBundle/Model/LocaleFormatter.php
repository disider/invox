<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Model;

use Symfony\Component\Translation\Translator;

class LocaleFormatter
{

    private $locale;
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator, $locale)
    {
        $this->locale = $locale;
        $this->translator = $translator;
    }

    public function format($value, $decimal = null, $decimalPoint = null, $thousandSep = null)
    {
        $defaults = $this->getDefaults();

        if (null === $decimal) {
            $decimal = $defaults[0];
        }

        if (null === $decimalPoint) {
            $decimalPoint = $defaults[1];
        }

        if (null === $thousandSep) {
            $thousandSep = $defaults[2];
        }

        return number_format((float)$value, $decimal, $decimalPoint, $thousandSep);
    }

    public function getDefaults()
    {
        switch ($this->translator->getLocale()) {
            case 'it':
                return [2, ',', '.'];
        }

        return [2, '.', ','];
    }

}