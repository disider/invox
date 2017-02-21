<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Twig;

use AppBundle\Model\LocaleFormatter;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class FormatExtension extends Twig_Extension
{
    /** @var \Twig_Extension_Core $core */
    private $core;

    /**
     * @var LocaleFormatter
     */
    private $localeFormatter;

    public function __construct(\Twig_Environment $twigEnvironment, LocaleFormatter $localeFormatter)
    {
        $this->core = $twigEnvironment->getExtension('core');
        $this->localeFormatter = $localeFormatter;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('currency', [$this, 'getCurrency'], [
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFunction('decimalPosition', [$this, 'getDecimalPosition']),
            new Twig_SimpleFunction('decimalPoint', [$this, 'getDecimalPoint']),
            new Twig_SimpleFunction('thousandSeparator', [$this, 'getThousandSeparator']),
            new Twig_SimpleFunction('filter_start', null, ['node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => ['html']]),
            new Twig_SimpleFunction('filter_end', null, ['node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('format_currency', [$this, 'formatCurrency'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFilter('format_number', [$this, 'formatNumber'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFilter('format_tax_rate', [$this, 'formatTaxRate'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
            ]),
            new Twig_SimpleFilter('format_sort', [$this, 'formatSort']),
        ];
    }

    public function formatSort(SlidingPagination $pagination, $key, $class = '')
    {
        if ($pagination->isSorted($key)) {
            return !empty($class) ? $class . ' sorted' : 'sorted';
        }

        return $class;
    }

    public function formatCurrency($number, $decimal = null, $decimalPoint = null, $thousandSep = null)
    {
        return $this->getCurrency() . '&nbsp;' . $this->formatNumber((float)$number, $decimal, $decimalPoint, $thousandSep);
    }

    public function formatTaxRate($number, $decimal = null, $decimalPoint = null, $thousandSep = null)
    {
        return $this->formatNumber((float)$number, $decimal, $decimalPoint, $thousandSep) . '%';
    }

    public function formatNumber($number, $decimal = null, $decimalPoint = null, $thousandSep = null)
    {
        return $this->localeFormatter->format($number, $decimal, $decimalPoint, $thousandSep);
    }

    public function getDecimalPosition()
    {
        $defaults = $this->localeFormatter->getDefaults();

        return $defaults[0];
    }

    public function getCurrency()
    {
        return '&euro;';
    }

    public function getDecimalPoint()
    {
        $defaults = $this->localeFormatter->getDefaults();

        return $defaults[1];
    }

    public function getThousandSeparator()
    {
        $defaults = $this->localeFormatter->getDefaults();

        return $defaults[2];
    }
}
