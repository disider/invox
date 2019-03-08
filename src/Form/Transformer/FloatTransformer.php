<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Form\Transformer;

use App\Model\LocaleFormatter;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FloatTransformer implements DataTransformerInterface
{
    /** @var LocaleFormatter */
    private $localeFormatter;

    public function __construct(LocaleFormatter $localeFormatter)
    {
        $this->localeFormatter = $localeFormatter;
    }

    /**
     * Transforms from DB value to form value
     * @param mixed $value
     * @return string
     */
    public function transform($value)
    {
        if ($value === null)
            return null;

        return $this->localeFormatter->format($value);
    }

    /**
     * Reverse transforms form value to db value
     * @param mixed $value
     * @return float
     */
    public function reverseTransform($value)
    {
        if (empty($value))
            return null;

        $defaults = $this->localeFormatter->getDefaults();
        $decimalPoint = $defaults[1];
        $thousandSep = $defaults[2];

        $value = str_replace($decimalPoint, '#', $value);
        $value = str_replace($thousandSep, '', $value);
        $value = str_replace('#', '.', $value);

        if (!is_numeric($value)) {
            throw new TransformationFailedException('Cannot convert value to number');
        }

        return (float)$value;
    }
}