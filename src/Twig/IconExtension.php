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

class IconExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('fa_icon', [$this, 'showIcon'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('icon', [$this, 'showIcon'], ['is_safe' => ['html']]),
        ];
    }

    public function showIcon($icon, $class = '')
    {
        return sprintf('<span class="fa fa-%s %s"></span>', $icon, $class ? 'fa-'.$class : '');
    }
}
