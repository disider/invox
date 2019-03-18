<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Twig;

use Twig_SimpleFilter;
use Twig_SimpleFunction;

class IconExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter(
                'parse_icons',
                [$this, 'parseIcon'],
                ['pre_escape' => 'html', 'is_safe' => ['html']]
            ),
        ];
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('fa_icon', [$this, 'showIcon'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('icon', [$this, 'showIcon'], ['is_safe' => ['html']]),
        ];
    }

    public function showIcon($icon, $class = '')
    {
        return sprintf('<span class="fa fa-%s %s"></span>', $icon, $class ? 'fa-'.$class : '');
    }

    public function parseIcon($text)
    {
        $that = $this;

        return preg_replace_callback(
            '/\.([a-z]+)-([a-z0-9+-]+)/',
            function ($matches) use ($that) {
                return $that->showIcon($matches[2], $matches[1]);
            },
            $text
        );
    }
}
